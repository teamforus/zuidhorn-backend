<?php

namespace App\Services\BunqService;

use Illuminate\Support\ServiceProvider;

class BunqServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('bunq', function () {
            return new BunqService();
        });
    }
}