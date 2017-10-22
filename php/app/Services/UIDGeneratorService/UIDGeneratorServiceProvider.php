<?php

namespace App\Services\UIDGeneratorService;

use Illuminate\Support\ServiceProvider;
use App\Services\UIDGeneratorService\UIDGenerator;

class UIDGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('uid_generator', function () {
            return new UIDGenerator();
        });
    }
}