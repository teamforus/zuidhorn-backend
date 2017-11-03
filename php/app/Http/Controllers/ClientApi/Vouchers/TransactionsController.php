<?php

namespace App\Http\Controllers\ClientApi\Vouchers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionsController extends Controller
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
        // target user and voucher
        $user = $request->user();
        $voucher = $user->vouchers->first();

        // fetch transaction details
        return $voucher->transactions->load('shop_keeper')->map(function($transaction) {
            $transaction->prety_date = date("M d, Y H:i", $transaction->created_at->timestamp);
            $transaction->shopKeeper = $transaction->shop_keeper->name;

            unset($transaction->shop_keeper);
            
            return $transaction;
        });
    }
}
