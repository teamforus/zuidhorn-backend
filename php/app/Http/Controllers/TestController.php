<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Voucher;
use App\Models\User;

use App\Services\KvkApiService\Facades\KvkApi;
use App\Services\BlockchainApiService\Facades\BlockchainApi;

use GuzzleHttp\Client;

class TestController extends Controller
{
    public function getTest(Request $request)
    {
        return \App\Models\ShopKeeper::whereUserId(2)->first()->makeBlockchainAccount();

        return BlockchainApi::requestFunds(
            '0x37e00c6a9c09390db9fc501cbbb2b4e633977732',
            '0xaf93496c88f4bcb117143f5908074b9d7a2d0a86',
            'B32MHJTGHAMO5GRJJ2F2CBFRYWU8TPJT',
            10
            );

        return BlockchainApi::getBalance("0x37e00c6a9c09390db9fc501cbbb2b4e633977732", true);

        return BlockchainApi::setShopKeeperState("0xaf93496c88f4bcb117143f5908074b9d7a2d0a86", true);

        return BlockchainApi::checkShopKeeperState("0xaf93496c88f4bcb117143f5908074b9d7a2d0a86");

        return BlockchainApi::createAccount("private_key");

        return BlockchainApi::batchVouchers(array(
            "323" => array(
                "private"=> "mc3nu9w3rcw", 
                "funds"=> 300
                ), 
            "523" => array(
                "private"=> "42308423", 
                "funds"=> 600
                ), 
            "4534" => array(
                "private"=> "1cm232", 
                "funds"=> 400
                ), 
            "3452" => array(
                "private"=> "sdflsef", 
                "funds"=> 400
                ), 
            "343" => array(
                "private"=> "ac33cr", 
                "funds"=> 599
                )
            ));

        return;

        $voucher = Voucher::whereCode('VIES-2F9M-J8RR-TC5W')->first();
        $response = $voucher->transactions->sum('amount');

        return compact('response');
    }
}