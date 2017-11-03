<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\Media;
use App\Models\Office;
use App\Models\OfficeSchedule;
use App\Models\ShopKeeper;

use App\Http\Requests\Api\OfficeStoreRequest;
use App\Http\Requests\Api\OfficeUpdateRequest;
use App\Http\Requests\Api\OfficeUpdateImageRequest;
use App\Http\Controllers\Controller;

use App\Jobs\UpdateOfficeCoordinatesJob;

class OfficeController extends Controller
{
    /**
     * Get list all shopkeeper's offices.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function index(
        Request $request
    ) {
        // current shopkeeper
        $shopKeeper = ShopKeeper::whereUserId(
            $request->user()->id
        )->first();

        // load details
        return $shopKeeper->offices->map(function($office) {
            $office->schedules = $office->schedules()
            ->select(['start_time', 'end_time'])->get();

            $office->preview = $office->urlPreview();
            $office->original = $office->urlOriginal();

            return $office;
        });
    }

    /**
     * Store new office.
     *
     * @param  \App\Http\Requests\Api\OfficeStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        OfficeStoreRequest $request
    ) {
        // current shopkeeper
        $shopKeeper = ShopKeeper::whereUserId(
            $request->user()->id
        )->first();

        // create new office owned by shopkeeper
        $office = $request->only(['address', 'phone', 'email']);
        $office['shop_keeper_id'] = $shopKeeper->id;
        $office = Office::create($office);

        // save office schedule
        foreach(collect($request->input('schedules')) as $key => $schedule) {
            if ($schedule['start_time'] == 'none') {
                $schedule = [
                    'start_time' => null,
                    'end_time' => null,
                ];
            } else {
                $schedule = collect($schedule)->only([
                    'start_time', 
                    'end_time'
                ])->toArray();
            }

            OfficeSchedule::firstOrCreate([
                'office_id' => $office->id,
                'week_day' => (intval($key) + 1),
            ])->update($schedule);
        }

        // update coordinates by address string
        UpdateOfficeCoordinatesJob::dispatch($office)->onQueue('high');

        return $this->show($request, $office);
    }

    /**
     * Get office details.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  \App\Models\Office           $office
     * @return \Illuminate\Http\Response
     */
    public function show(
        Request $request, 
        Office $office
    ) {
        // current shopkeeper
        $shopKeeper = ShopKeeper::whereUserId(
            $request->user()->id
        )->first();

        // check access
        if ($office->shop_keeper_id != $shopKeeper->id) {
            return response(collect([
                'error'     => 'office-access-violation',
                'message'   => "Shopkeeper may edit only owned offices."
            ]), 401);
        }

        // load details
        $office->schedules = $office->schedules()
        ->select(['start_time', 'end_time'])->get();

        $office->preview = $office->urlPreview();
        $office->original = $office->urlOriginal();

        // show
        return collect($office)->only([
            'id', 'address', 'phone', 'email', 'created_at', 'updated_at', 
            'schedules', 'preview', 'original'
        ]);
    }

    /**
     * Update the specified office in storage.
     *
     * @param  \App\Http\Requests\Api\OfficeUpdateRequest   $request
     * @param  \App\Models\Office                           $office
     * @return \Illuminate\Http\Response
     */
    public function update(
        OfficeUpdateRequest $request, 
        Office $office
    ) {
        // current shopkeeper
        $shopKeeper = ShopKeeper::whereUserId(
            $request->user()->id
        )->first();

        // check access
        if ($office->shop_keeper_id != $shopKeeper->id) {
            return response(collect([
                'error'     => 'office-access-violation',
                'message'   => "Shopkeeper may edit only owned offices."
            ]), 401);
        }

        // update office details
        $office->update($request->only(['address', 'phone', 'email']));

        // update office schedule details
        foreach(collect($request->input('schedules')) as $key => $schedule) {
            if ($schedule['start_time'] == 'none') {
                $schedule = [
                    'start_time' => null,
                    'end_time' => null,
                ];
            } else {
                $schedule = collect($schedule)->only([
                    'start_time', 
                    'end_time'
                ])->toArray();
            }

            OfficeSchedule::firstOrCreate([
                'office_id' => $office->id,
                'week_day' => (intval($key) + 1),
            ])->update($schedule);
        }

        // update coordinates by address string
        UpdateOfficeCoordinatesJob::dispatch($office)->onQueue('high');

        return [];
    }

    /**
     * Remove the specified office from storage.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  \App\Models\Office           $office
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        Request $request, 
        Office $office
    ) {
        // current shopkeeper
        $shopKeeper = ShopKeeper::whereUserId(
            $request->user()->id
        )->first();

        // check access
        if ($office->shop_keeper_id != $shopKeeper->id) {
            return response(collect([
                'error'     => 'office-access-violation',
                'message'   => "Shopkeeper may edit only owned offices."
            ]), 401);
        }

        if ($shopKeeper->offices()->count() <= 1) {
            return response(collect([
                'error'     => 'offices min number',
                'message'   => "At least one office is required."
            ]), 401);
        }

        // remove office and resources
        $office->unlink();

        return [];
    }


    /**
     * Update the specified office in storage.
     *
     * @param  \App\Http\Requests\Api\OfficeUpdateImageRequest  $request
     * @param  \App\Models\Office                               $office
     * @return \Illuminate\Http\Response
     */
    public function updateImage(
        OfficeUpdateImageRequest $request, 
        Office $office
    ) {
        // current shopkeeper
        $shopKeeper = ShopKeeper::whereUserId(
            $request->user()->id
        )->first();

        // validate image input
        $this->validate($request, [
            'image' => 'required|image'
        ]);

        // check access
        if ($office->shop_keeper_id != $shopKeeper->id) {
            return response(collect([
                'error'     => 'office-access-violation',
                'message'   => "Shopkeeper may edit only owned offices."
            ]), 401);
        }

        // media details
        $original_type  = 'original';
        $preview_type   = 'preview';
        $mediable_type  = Office::class;
        $mediable_id    = $office->id;

        // upload photo
        $media_info = Media::uploadSingle(
            $original_type, $mediable_type, 'image');

        // confirm uploaded photo
        $media = Media::confirmSingle(
            $original_type, $mediable_type, 
            $mediable_id, $media_info['mediaId']);

        $media_info = Media::uploadSingle(
            $preview_type, $mediable_type, 'image');

        // confirm uploaded photo
        $media = Media::confirmSingle(
            $preview_type, $mediable_type, 
            $mediable_id, $media_info['mediaId']);

        $image = \Intervention\Image\Facades\Image::make(
            $request->file('image')
        )->encode('data-url')->encoded;

        return compact('image');
    }

    /**
     * Get count all shopkeeper offices.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function count(
        Request $request
    ) {
        // current shopkeeper
        $shopKeeper = ShopKeeper::whereUserId(
            $request->user()->id
        )->first();

        return [
            'count' => $shopKeeper->offices()->count()
        ];
    }
}
