<?php

namespace App\Services\BlockchainApiService;

use Illuminate\Support\ServiceProvider;
use App\Services\BlockchainApiService\BlockchainApi;

class BlockchainApiServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('blockchain_api', function () {
            return new BlockchainApi();
        });
    }
}