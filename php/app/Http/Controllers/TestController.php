<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\Models\Transaction;
use App\Models\Voucher;
use App\Models\Wallet;
use App\Models\User;

use App\Jobs\BunqProcessTransactionJob;
use App\Services\KvkApiService\Facades\KvkApi;
use App\Services\BlockchainApiService\Facades\BlockchainApi;

use GuzzleHttp\Client;

class TestController extends Controller
{
    public function getTest(Request $request)
    {
        return BlockchainApi::generateWallet();
    }
}