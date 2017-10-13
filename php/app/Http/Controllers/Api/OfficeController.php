<?php

namespace App\Http\Controllers\Api;

use App\Models\Media;
use App\Models\Office;
use App\Models\OfficeSchedule;
use App\Models\ShopKeeper;

use App\Http\Requests\Api\OfficeStoreRequest;
use App\Http\Requests\Api\OfficeUpdateRequest;
use App\Http\Requests\Api\OfficeUpdateImageRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $target_user = $request->user();
        $shop_keeper = ShopKeeper::whereUserId($target_user->id)->first();

        $offices = $shop_keeper->offices;

        $offices->map(function($office) {
            $office->schedules = $office->schedules()
            ->select(['start_time', 'end_time'])->get();

            $office->preview = $office->urlPreview();
            $office->original = $office->urlOriginal();

            return $office;
        });

        return $offices;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\OfficeStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfficeStoreRequest $request)
    {
        $target_user = $request->user();
        $shop_keeper = ShopKeeper::whereUserId($target_user->id)->first();

        $office = $request->only(['address', 'phone', 'email']);
        $office['shop_keeper_id'] = $shop_keeper->id;

        $office = Office::create($office);

        foreach(collect($request->input('schedules')) as $key => $schedule) {
            OfficeSchedule::firstOrCreate([
                'office_id' => $office->id,
                'week_day' => (intval($key) + 1),
            ])->update(
                collect($schedule)->only([
                    'start_time', 'end_time'
                ])->toArray()
            );
        }

        $office->updateCoordinates();

        return $this->show($office);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function show(Office $office)
    {
        $office->schedules = $office->schedules()
        ->select(['start_time', 'end_time'])->get();

        $office->preview = $office->urlPreview();
        $office->original = $office->urlOriginal();

        return collect($office)->only([
            'id', 'address', 'phone', 'email', 'created_at', 'updated_at', 
            'schedules', 'preview', 'original'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function edit(Office $office)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\OfficeUpdateRequest  $request
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function update(OfficeUpdateRequest $request, Office $office)
    {
        $office->update($request->only(['address', 'phone', 'email']));

        foreach(collect($request->input('schedules')) as $key => $schedule) {
            OfficeSchedule::firstOrCreate([
                'office_id' => $office->id,
                'week_day' => (intval($key) + 1),
            ])->update(
                collect($schedule)->only([
                    'start_time', 'end_time'
                ])->toArray()
            );
        }

        $office->updateCoordinates();

        return $this->show($office);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\OfficeUpdateImageRequest  $request
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function updateImage(OfficeUpdateImageRequest $request, Office $office)
    {
        $this->validate($request, [
            'image' => 'required|image'
        ]);

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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function destroy(Office $office)
    {
        //
    }

    /**
     * Get count all shopkeeper offices.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function count(Request $request)
    {
        $target_user = $request->user();
        $shop_keeper = ShopKeeper::whereUserId($target_user->id)->first();

        return ['count' => $shop_keeper->offices()->count()];
    }
}
