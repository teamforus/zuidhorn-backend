<?php

namespace App\Http\Controllers\MunicipalityApi;

use App\Models\ShopKeeper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Collection;
use \Symfony\Component\HttpFoundation\Response;
use App\Services\BlockchainApiService\Facades\BlockchainApi;

class ShopKeeperController extends Controller
{
    /**
     * Display a listing of the shopkeepers.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Collection|Response|static
     */
    public function index(Request $request)
    {
        if (!$request->user()->hasPermission('shopkeeper_manage')) {
            return response([], 401);
        }

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
     * @param Request $request
     * @param ShopKeeper $shopKeeper
     * @return $this|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function state(Request $request, ShopKeeper $shopKeeper)
    {
        if (!$request->user()->hasPermission('shopkeeper_manage')) {
            return response([], 401);
        }

        if (is_numeric($request->shopKeeper)) {
            $shopKeeper = ShopKeeper::find($request->shopKeeper);
        }

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

        return response([
            "success" => !!$shopKeeper->changeState($request->input('state'))
        ]);
    }

    /**
     * Get shopkeepers with earnings overview
     * @return mixed
     */
    public function earnings() {
        $shopKeepers = app(Shopkeeper::class)->with([
            'user', 'categories'
        ])->get();

        return $shopKeepers->map(function($shopKeeper) {
            /**
             * @var ShopKeeper $shopKeeper
             */
            $weekAgoDate = Carbon::now()->subWeek()->format('Y-m-d H:i:s');
            $monthAgoDate = Carbon::now()->subMonth()->format('Y-m-d H:i:s');

            $total = $shopKeeper->transactions()->where([
                'status' => 'success'
            ])->sum('amount');

            $last_month = $shopKeeper->transactions()->where([
                'status' => 'success'
            ])->where('created_at', '>', $monthAgoDate)->sum('amount');

            $last_week = $shopKeeper->transactions()->where([
                'status' => 'success'
            ])->where('created_at', '>', $weekAgoDate)->sum('amount');

            $debs = $shopKeeper->transactions()->where([
                'status' => 'refund'
            ])->sum('amount');

            $transactions = $shopKeeper->transactions()->select([
                'status', 'created_at', 'amount'
            ])->get();

            return collect($shopKeeper)->merge([
                'earnings' => compact('total', 'last_month', 'last_week', 'debs'),
                'transactions' => $transactions
            ]);
        });
    }
}