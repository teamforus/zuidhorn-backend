<?php

namespace App\Http\Controllers\Panel;

use App\Http\Requests\Panel\ShopKeeperOfficeStoreRequest;
use App\Http\Requests\Panel\ShopKeeperOfficeUpdateRequest;

use App\Models\Media;
use App\Models\ShopKeeper;
use App\Models\ShopKeeperOffice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopKeeperOfficeController extends Controller
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
        $this->authorize('create', ShopKeeperOffice::class);

        return $this->_make('panel', 'shop_keeper_offices-edit', compact('edit', 'shopKeeper'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Panel\ShopKeeperOfficeStoreRequest  $request
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function store(ShopKeeperOfficeStoreRequest $request, ShopKeeper $shopKeeper)
    {
        if (!$shopKeeper->id)
            abort(404);

        $office = $request->only(['address']);
        $coordinates = ShopKeeperOffice::getCoordinates($office['address']);

        $office['lon'] = $coordinates['lng'];
        $office['lat'] = $coordinates['lat'];
        $office['shop_keeper_id'] = $shopKeeper->id;

        if ($office = ShopKeeperOffice::create($office)) {
            session()->flash('alert_default', 'Office created!');

            $edit = $office;

            // media details
            $original_type  = 'original';
            $preview_type   = 'preview';
            $mediable_type  = ShopKeeperOffice::class;
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
     * Display the specified resource.
     *
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @param  \App\Models\ShopKeeperOffice  $shopKeeperOffice
     * @return \Illuminate\Http\Response
     */
    public function show(ShopKeeper $shopKeeper, ShopKeeperOffice $shopKeeperOffice)
    {
        $view = $shopKeeperOffice;
        $this->authorize('view', $view);

        return $this->_make('panel', 'shop_keeper_offices-view', compact('view'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @param  \App\Models\ShopKeeperOffice  $shopKeeperOffice
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopKeeper $shopKeeper, ShopKeeperOffice $shopKeeperOffice)
    {
        $edit = $shopKeeperOffice;
        $this->authorize('update', $edit);

        return $this->_make('panel', 'shop_keeper_offices-edit', compact('edit', 'shopKeeper'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Panel\ShopKeeperOfficeUpdateRequest  $request
     * @param  \App\Models\ShopKeeperOffice  $shopKeeperOffice
     * @return \Illuminate\Http\Response
     */
    public function update(ShopKeeperOfficeUpdateRequest $request, ShopKeeper $shopKeeper, ShopKeeperOffice $shopKeeperOffice)
    {
        $edit = $shopKeeperOffice;

        if ($edit->update($request->only(['address', 'lon', 'lat']))) {
            session()->flash('alert_default', 'Office updated!');

            // media details
            $original_type  = 'original';
            $preview_type   = 'preview';
            $mediable_type  = ShopKeeperOffice::class;
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
     * @param  \App\Models\ShopKeeperOffice  $shopKeeperOffice
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopKeeper $shopKeeper, ShopKeeperOffice $shopKeeperOffice)
    {
        $this->authorize('delete', $shopKeeperOffice);
        
        if ($shopKeeperOffice->unlink())
            session()->flash('alert_default', 'Shop Keeper Office deleted!');

        return redirect()->back();
    }
}
