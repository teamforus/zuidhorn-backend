<?php

namespace App\Http\Controllers\Api\Vouchers;

use App\Models\Refund;
use App\Models\Voucher;
use App\Models\ShopKeeper;
use App\Models\Transaction;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    /**
     * Returns list transactions common for current shopkeeper 
     * and the voucher.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  \App\Models\Voucher          $voucher
     * @return \Illuminate\Http\Response
     */
    public function index(
        Request $request, 
        Voucher $voucher
    ) {
        // current shopkeeper
        $shopKeeper = (new ShopKeeper())->where([
            'user_id' => $request->user()->id
        ])->first();

        // get voucher transaction shared by shopkeeper
        return $voucher->transactions()->where([
            'shop_keeper_id' => $shopKeeper->id,
        ])->get();
    }

    /**
     * Processing new transaction request.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  \App\Models\Voucher          $voucher
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request, 
        Voucher $voucher
    ) {
        // current shopkeeper
        $shopKeeper = (new ShopKeeper())->where([
            'user_id' => $request->user()->id
        ])->first();

        // transaction input
        $amount         = $request->input('amount');
        $min_amount     = 0.01;
        $max_amount     = $voucher->getAvailableFunds();
        $full_amount    = $request->input('full_amount');
        $extra_amount   = $request->input('extra_amount', 0);

        // validate input
        $this->validate($request, [
            "full_amount"   => "nullable|boolean",
            "amount"        => "required|numeric|between:{$min_amount},{$max_amount}",
            "extra_amount"  => "nullable|numeric"
        ]);

        // get categories shared by voucher and shopkeeper
        $available_categories = $shopKeeper->categories->pluck('id')
        ->intersect($voucher->budget->categories->pluck('id'));

        // at least one common category is required for transaction
        if ($available_categories->count() < 1) {
            return response(collect([
                'error'     => 'voucher-unavailable-categories',
                'message'   => "Shopkeeper don't have categories required by voucher."
            ]), 401);
        }

        // start transaction process
        return collect($voucher->makeTransaction(
            $shopKeeper->id,
            $full_amount ? $max_amount : $amount,
            $extra_amount))->only([
                'id', 'amount', 'extra_amount', 'created_at', 'updated_at'
        ]);
    }

    /**
     * Return requested transaction common for current shopkeeper
     * and the voucher.
     *
     * @param Request $request
     * @param Voucher $voucher
     * @param Transaction $transaction
     * @return Transaction|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function show(
        Request $request, 
        Voucher $voucher, 
        Transaction $transaction
    ) {
        // current shopkeeper
        $shopKeeper = (new ShopKeeper())->where([
            'user_id' => $request->user()->id
        ])->first();

        // check transaction -> voucher relations
        if ($transaction->voucher_id != $voucher->id)
            return response([
                'error'     => 'wrong-transaction-voucher',
                'message'   => 'This transaction does not belong to provided voucher'
            ], 401);

        // check transaction -> shopkeeper relations
        if ($transaction->shop_keeper_id != $shopKeeper->id)
            return response([
                'error'     => 'wrong-transaction-shopkeeper',
                'message'   => 'This transaction does not belong to current shopkeeper'
            ], 401);

        // return target transaction
        return $transaction;
    }

    /**
     * Mark transaction to be refunded.
     *
     * @param Request $request
     * @param Voucher $voucher
     * @param Transaction $transaction
     * @return Transaction|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function refund(
        Request $request, 
        Voucher $voucher, 
        Transaction $transaction
    ) {
        // current shopkeeper
        $shopKeeper = (new ShopKeeper())->where([
            'user_id' => $request->user()->id
        ])->first();

        // check transaction -> voucher relations
        if ($transaction->voucher_id != $voucher->id)
            return response([
                'error'     => 'wrong-transaction-voucher',
                'message'   => 'This transaction does not belong to provided voucher'
            ], 401);

        // check transaction -> shopkeeper relations
        if ($transaction->shop_keeper_id != $shopKeeper->id)
            return response([
                'error'     => 'wrong-transaction-shopkeeper',
                'message'   => 'This transaction does not belong to current shopkeeper'
            ], 401);

        // check target transaction state
        if ($transaction->status == 'refunded' || 
            $transaction->status == 'refund')
            return response([
                'error'     => 'already-marked',
                'message'   => "Transaction is already marked for refunding."
            ], 401);

        // transaction was not executed, make sure it will not happen
        if ($transaction->status == 'pending') {
            $transaction->update([
                'status' => 'refunded'
            ]);

            return $transaction;
        }

        // get current refund if any exists
        $refund = (new Refund())->where([
            'shop_keeper_id'    => $shopKeeper->id,
            'status'            => 'pending',
        ])->first();

        // check bunq request status,
        // update refund and transactions status
        if($refund) {
            $refund->applyOrRevokeBunqRequest();
        }

        // prepare transaction for refund
        $transaction->update([
            'status' => 'refund'
        ]);

        // create new pending refund
        (new Refund())->create([
            'shop_keeper_id'    => $shopKeeper->id,
            'status'            => 'pending',
        ])->transactions()->attach($shopKeeper->transactions()->where([
            'status' => 'refund'
        ])->pluck('id'));

        // return target transaction
        return $transaction;
    }
}
