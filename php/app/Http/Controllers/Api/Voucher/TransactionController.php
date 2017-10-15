<?php

namespace App\Http\Controllers\Api\Voucher;

use App\Models\Refund;
use App\Models\Voucher;
use App\Models\ShopKeeper;
use App\Models\Transaction;

use App\Http\Requests\App\VoucherSubmitRequest;
use App\Services\BlockchainApiService\Facades\BlockchainApi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Voucher $voucher)
    {
        $user = $request->user();
        $shop_keeper = ShopKeeper::whereUserId($user->id)->first();

        return $voucher->transactions()->where([
            'shop_keeper_id' => $shop_keeper->id,
        ])->get();
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
    public function store(Request $request, Voucher $voucher)
    {
        if (!$voucher->id)
            return response(collect([
                'message' => 'Voucher not found!'
            ]), 404);

        $user = $request->user();
        $shop_keeper = ShopKeeper::whereUserId($user->id)->first();

        $available_categs = $shop_keeper->categories->pluck('id')->intersect(
            $voucher->budget->categories->pluck('id'));

        if ($available_categs->count() < 1)
            return response(collect([
                'error' => 'no-available-categories',
                'message' => "Shopkeeper don't have categories required by voucher."
            ]), 401);

        $max_amount = $voucher->getAvailableFunds();

        $amount = $request->input('full_amount') ? $max_amount : $request->input('amount');
        $extra_amount = $request->input('extra_amount');

        if (!$extra_amount)
            $extra_amount = 0;

        return $voucher->logTransaction($shop_keeper->id, $amount, $extra_amount);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Voucher $voucher, Transaction $transaction)
    {
        return $transaction;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function refund(Request $request, Voucher $voucher, Transaction $transaction)
    {
        $user = $request->user();
        $shopKeeper = ShopKeeper::whereUserId($user->id)->first();

        if (collect(['refund', 'refunded'])
            ->search($transaction->status) !== false)
            return response([
                'error' => 'already-marked',
                'message' => "Transaction is already marked for refunding."
            ], 401);

        $refund = Refund::where([
            'shop_keeper_id'    => $shopKeeper->id,
            'status'            => 'pending',
        ])->first();

        // check bunq request status,
        // update refund and transactions status
        if($refund) {
            $refund->applyOrRevokeBunqRequest();
        }

        $transaction->update(['status' => 'refund']);        

        BlockchainApi::requestFunds(
            $shopKeeper->user->public_key,
            $transaction->voucher->public_key,
            $transaction->voucher->private_key,
            $transaction->amount
        );

        $refund = Refund::create([
            'shop_keeper_id'    => $shopKeeper->id,
            'status'            => 'pending',
        ]);

        $refund->transactions()->attach($shopKeeper->transactions()->where([
            'status' => 'refund'
        ])->pluck('id'));

        return $transaction;
    }
}
