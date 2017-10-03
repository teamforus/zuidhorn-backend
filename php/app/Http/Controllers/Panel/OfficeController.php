<?php

namespace App\Http\Controllers\Panel;

use App\Http\Requests\Panel\OfficeStoreRequest;
use App\Http\Requests\Panel\OfficeUpdateRequest;

use App\Models\Media;
use App\Models\ShopKeeper;
use App\Models\Office;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ShopKeeper $shopKeeper)
    {
        $edit = false;
        $this->authorize('create', Office::class);

        return $this->_make('panel', 'offices-edit', compact('edit', 'shopKeeper'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Panel\OfficeStoreRequest  $request
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function store(OfficeStoreRequest $request, ShopKeeper $shopKeeper)
    {
        if (!$shopKeeper->id)
            abort(404);

        $office['address'] = $request->input('address');
        $office['shop_keeper_id'] = $shopKeeper->id;

        if ($office = Office::create($office)) {
            session()->flash('alert_default', 'Office created!');

            $edit = $office;

            // media details
            $original_type  = 'original';
            $preview_type   = 'preview';
            $mediable_type  = Office::class;
            $mediable_id    = $edit->id;

            // upload photo
            if ($request->file('image')) {
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
            }

            $office->updateCoordinates();
        }

        return redirect($shopKeeper->urlPanelView());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @param  \App\Models\Office  $shopKeeperOffice
     * @return \Illuminate\Http\Response
     */
    public function show(ShopKeeper $shopKeeper, Office $shopKeeperOffice)
    {
        $view = $shopKeeperOffice;
        $this->authorize('view', $view);

        return $this->_make('panel', 'offices-view', compact('view'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @param  \App\Models\Office  $shopKeeperOffice
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopKeeper $shopKeeper, Office $shopKeeperOffice)
    {
        $edit = $shopKeeperOffice;
        $this->authorize('update', $edit);

        return $this->_make('panel', 'offices-edit', compact('edit', 'shopKeeper'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Panel\OfficeUpdateRequest  $request
     * @param  \App\Models\Office  $shopKeeperOffice
     * @return \Illuminate\Http\Response
     */
    public function update(OfficeUpdateRequest $request, ShopKeeper $shopKeeper, Office $shopKeeperOffice)
    {
        $edit = $shopKeeperOffice;

        if ($edit->update($request->only(['address', 'lon', 'lat']))) {
            session()->flash('alert_default', 'Office updated!');

            // media details
            $original_type  = 'original';
            $preview_type   = 'preview';
            $mediable_type  = Office::class;
            $mediable_id    = $edit->id;

            // upload photo
            if ($request->file('image')) {
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
            }
        }

        return redirect($shopKeeper->urlPanelView());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @param  \App\Models\Office  $shopKeeperOffice
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopKeeper $shopKeeper, Office $shopKeeperOffice)
    {
        $this->authorize('delete', $shopKeeperOffice);
        
        if ($shopKeeperOffice->unlink())
            session()->flash('alert_default', 'Shop Keeper Office deleted!');

        return redirect()->back();
    }
}
