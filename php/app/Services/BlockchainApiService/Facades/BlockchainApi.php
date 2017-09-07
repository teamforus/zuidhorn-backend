<?php

namespace App\Services\BlockchainApiService\Facades;

use Illuminate\Support\Facades\Facade;

class BlockchainApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'blockchain_api';
    }
}
