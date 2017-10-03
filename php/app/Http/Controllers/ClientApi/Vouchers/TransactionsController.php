<?php

namespace App\Http\Controllers\ClientApi\Vouchers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $voucher = $user->vouchers->first();

        return $voucher->transactions->map(function($transaction) {
            $transaction->prety_date = date("M d, Y H:i", $transaction->created_at->timestamp);

            return $transaction;
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
     * @param  \App\Models\Transaction  $voucherTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $voucherTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $voucherTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $voucherTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $voucherTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $voucherTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $voucherTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $voucherTransaction)
    {
        //
    }
}
