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

    public function state(Request $request, ShopKeeper $shopKeeper)
    {
        $shopKeeper = ShopKeeper::find($request->shopKeeper);

        // validation
        $this->validate($request, [
            'state' => 'required|in:pending,declined,approved',
        ]);

        /*if ($request->input('state') == 'approved') {
            if ($request->shopKeeper->offices()->count() < 1) {
                return response(collect([
                    'error' => 'no-offices',
                    'message' => 'Shopkeeper should add at least one office.'
                ]), 401);
            }

            if ($request->shopKeeper->categories()->count() < 1) {
                return response(collect([
                    'error' => 'no-categories',
                    'message' => 'Shopkeeper should add at least one category.'
                ]), 401);
            }
        }*/

        return $shopKeeper->changeState($request->input('state'));
    }
}