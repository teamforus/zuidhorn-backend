<?php

namespace App\Http\Controllers\MunicipalityApi;

use App\Models\ShopKeeper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BlockchainApiService\Facades\BlockchainApi;

class ShopKeeperController extends Controller
{
    /**
     * Display a listing of the shopkeepers.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->hasPermission('shopkeeper_manage'))
            return response([], 401);

        return ShopKeeper::with(
            'offices.schedules', 'user', 'categories'
        )->get()->map(function($shopKeeper) {
            $shopKeeper->preview = $shopKeeper->urlPreview();
            $shopKeeper->original = $shopKeeper->urlOriginal();
            
            $shopKeeper->offices = $shopKeeper->offices->map(
                function($office) {
                    $office->preview = $office->urlPreview();
                    $office->original = $office->urlOriginal();

                    return $office;
                });

            return $shopKeeper;
        });
    }

    /**
     * Change the shopkeeper state.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  \App\Models\ShopKeeper       $shopKeeper
     * @return \Illuminate\Http\Response
     */
    public function state(Request $request, ShopKeeper $shopKeeper)
    {
        if (!$request->user()->hasPermission('shopkeeper_manage'))
            return response([], 401);

        if (is_numeric($request->shopKeeper))
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