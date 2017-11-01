<?php

namespace App\Http\Controllers\Api;

use App\Models\Refund;
use App\Models\ShopKeeper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RefundController extends Controller
{
    /**
     * Amount all funds marked for refunding.
     * 
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function amount(
        Request $request
    ) {
        // current shopkeeper
        $shopKeeper = ShopKeeper::whereUserId(
            $request->user()->id
        )->first();

        $refund = $shopKeeper->refunds()->where([
            'status' => 'pending'
        ])->first();

        if (!$refund)
            $amount = 0;
        else
            $amount = $refund->transactions()->where([
                'status' => 'pending-refund'
            ])->sum('amount');

        return compact('amount');
    }

    /**
     * Generate link for the refund payment.
     * 
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function link(
        Request $request
    ) {
        // current shopkeeper
        $shopKeeper = ShopKeeper::whereUserId(
            $request->user()->id
        )->first();
        
        $refund = $shopKeeper->refunds()->where([
            'status' => 'pending'
        ])->first();

        if (!$refund)
            return response([
                'error'         => 'nothing-to-refund',
                'description'   => 'Nothing to refund.',
            ], $status = 401);

        $url = $refund->getBunqUrl();

        return compact('url');
    }
}
