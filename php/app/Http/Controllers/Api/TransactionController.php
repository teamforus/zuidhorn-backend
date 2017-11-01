<?php

namespace App\Http\Controllers\Api;

use App\Models\ShopKeeper;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    /**
     * Display a listing of the transactions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(
        Request $request
    ) {
        // current shopkeeper
        $shopKeeper = ShopKeeper::whereUserId(
            $request->user()->id
        )->first();

        return $shopKeeper->transactions;
    }

    /**
     * Get count all shopkeeper transactions.
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

        return ['count' => $shopKeeper->transactions()->count()];
    }
}
