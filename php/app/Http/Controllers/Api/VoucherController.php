<?php

namespace App\Http\Controllers\Api;

use App\Models\Voucher;
use App\Models\ShopKeeper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VoucherController extends Controller
{
    /**
     * Return requestd voucher details.
     * Only if they have shared categories.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function show(
        Request $request, 
        Voucher $voucher
    ) {
        // current shopkeeper
        $shopKeeper = ShopKeeper::whereUserId(
            $request->user()->id
        )->first();

        // get categories shared by voucher and shopkeeper
        $available_categs = $shopKeeper->categories->pluck('id')
        ->intersect($voucher->budget->categories->pluck('id'));

        // at least one common category is required for transaction
        if ($available_categs->count() < 1) {
            return response(collect([
                'error'     => 'voucher-unavailable-categories',
                'message'   => "Shopkeeper don't have categories required by voucher."
            ]), 401);
        }

        // return voucher details
        return [
            'public_key'    => $voucher->wallet->address,
            'max_amount'    => $voucher->getAvailableFunds()
        ];
    }
}