<?php

namespace App\Http\Controllers\Api;

use App\Models\Refund;
use App\Models\ShopKeeper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RefundController extends Controller
{
    public function amount(Request $request) {
        $target_user = $request->user();
        $shop_keeper = ShopKeeper::whereUserId($target_user->id)->first();

        $refund = $shop_keeper->refunds()->where([
            'status' => 'pending'
        ])->first();

        if (!$refund)
            $amount = 0;
        else
            $amount = $refund->transactions()->where([
                'status' => 'refund'
            ])->sum('amount');

        return compact('amount');
    }

    public function link(Request $request) {
        $target_user = $request->user();
        $shop_keeper = ShopKeeper::whereUserId($target_user->id)->first();
        
        $refund = $shop_keeper->refunds()->where([
            'status' => 'pending'
        ])->first();

        if (!$refund)
            return response([
                'error' => 'nothing-to-refund',
                'description' => 'Nothing to refund.',
            ], $status = 401);

        $url = $refund->getBunqUrl();

        return compact('url');
    }
}
