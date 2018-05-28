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
        $shopKeeper = (new ShopKeeper())->where([
            'user_id' => $request->user()->id
        ])->first();
        
        // get current refund model
        /** @var Refund $refund */
        $refund = $shopKeeper->refunds()->where([
            'status' => 'pending'
        ])->first();

        // refund model exists, check state and remove model
        if ($refund) {
            $refund->updateState();
        }

        $amount = $shopKeeper->transactions()->where([
            'status' => 'refund'
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
        $shopKeeper = (new ShopKeeper())->where([
            'user_id' => $request->user()->id
        ])->first();
        
        // get current refund model
        /** @var Refund $refund */
        $refund = $shopKeeper->refunds()->where([
            'status' => 'pending'
        ])->first();

        // total funds to be refund
        $amount = $shopKeeper->transactions()->where([
            'status' => 'refund'
        ])->sum('amount');

        // nothing to refund
        if ($amount == 0) {
            return response([
                'error'         => 'nothing-to-refund',
                'description'   => 'Nothing to refund.',
            ], $status = 401);
        }

        // no refund model or amount is wrong
        if (!$refund || ($refund->transactions()->sum('amount') != $amount)) {
            // refund model exists, check state and remove model
            if ($refund) {
                $refund->applyOrRevokeBunqRequest();
            }

            // create new pending refund
            $refund = (new Refund())->create([
                'shop_keeper_id'    => $shopKeeper->id,
                'status'            => 'pending',
            ]);

            $refund->transactions()->attach($shopKeeper->transactions()->where([
                'status'            => 'refund'
            ])->pluck('id'));
        }

        $url = $refund ? $refund->getBunqUrl() : false;

        return compact('url');
    }
}
