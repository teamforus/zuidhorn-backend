<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        App::setLocale(Request::header('Locale', 'en'));
        Schema::defaultStringLength(200);

        Validator::extend('iban', '\App\Validators\IbanValidator@rule');
        Validator::extend('kvk_number', '\App\Validators\KvkNumberValidator@rule');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}