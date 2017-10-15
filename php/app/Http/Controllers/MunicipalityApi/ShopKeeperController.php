<?php

namespace App\Http\Controllers\MunicipalityApi;

use App\Models\ShopKeeper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BlockchainApiService\Facades\BlockchainApi;

class ShopKeeperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ShopKeeper::with('offices.schedules', 'user', 'categories')->get()->map(function($shopKeeper) {
            $shopKeeper->preview = $shopKeeper->urlPreview();
            $shopKeeper->original = $shopKeeper->urlOriginal();
            
            $shopKeeper->offices = $shopKeeper->offices->map(function($office) {
                $office->preview = $office->urlPreview();
                $office->original = $office->urlOriginal();

                return $office;
            });

            return $shopKeeper;
        });
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function show(ShopKeeper $shopKeeper)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function edit(ShopKeeper $shopKeeper)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShopKeeper $shopKeeper)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShopKeeper  $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopKeeper $shopKeeper)
    {
        //
    }

    public function state(Request $request)
    {
        $shopKeeper = ShopKeeper::find($request->shopKeeper);
        $shopKeeper->update($request->only('state'));

        if (!$shopKeeper->public_key && $request->input('state') == 'approved')
            $shopKeeper->makeBlockchainAccount();

        BlockchainApi::setShopKeeperState(
            $shopKeeper->user->public_key, 
            $request->input('state') == 'approved');

        return $shopKeeper;
    }
}
