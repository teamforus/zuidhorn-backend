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
        $amount = $request->input('amount');
        $max_amount = $voucher->getAvailableFunds();
        $full_amount = $request->input('full_amount');
        $extra_amount = $request->input('extra_amount', 0);

        $user = $request->user();
        $shop_keeper = ShopKeeper::whereUserId($user->id)->first();

        // validation
        $this->validate($request, [
            "full_amount"   => "nullable|boolean",
            "amount"        => "required|max:" . $max_amount,
            "extra_amount"  => "nullable|numeric"
        ]);

        // validate available categories
        $available_categs = $shop_keeper->categories->pluck('id');
        $available_categs = $available_categs->intersect(
            $voucher->budget->categories->pluck('id'));

        if ($available_categs->count() < 1)
            return response(collect([
                'error'     => 'no-available-categories',
                'message'   => "Shopkeeper don't have categories required by voucher."
            ]), 401);

        return $voucher->makeTransaction(
            $shop_keeper->id, 
            $full_amount ? $max_amount : $amount, 
            $extra_amount);
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

        if (collect(['pending-refund', 'refunded'])
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

        $transaction->update(['status' => 'pending-refund']);

        Refund::create([
            'shop_keeper_id'    => $shopKeeper->id,
            'status'            => 'pending',
        ])->transactions()->attach($shopKeeper->transactions()->where([
            'status' => 'pending-refund'
        ])->pluck('id'));

        return $transaction;
    }
}
