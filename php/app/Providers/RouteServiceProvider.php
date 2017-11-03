<?php

namespace App\Providers;

use App\Models\Device;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();

        Route::model('category', \App\Models\Category::class);
        Route::model('voucher', \App\Models\Voucher::class);
        
        // Route::model('shopKeeper', App\Models\ShopKeeper::class);
        Route::model('shopKeeperCategory', \App\Models\ShopKeeperCategory::class);
        
        Route::model('office', \App\Models\Office::class);
        Route::model('budget', \App\Models\Budget::class);
        Route::model('budgetCategory', \App\Models\BudgetCategory::class);
        
        Route::model('voucher_transaction', \App\Models\Transaction::class);
        Route::model('user', \App\Models\User::class);

        Route::bind('shopKeeper', function ($shopKeeper) {
            return \App\Models\ShopKeeper::find($shopKeeper);
        });

        Route::bind('device_approve_token', function ($token) {
            return \App\Models\Device::whereApproveToken($token)->first();
        });

        Route::bind('voucher_code', function ($code) {
            return \App\Models\Voucher::whereCode($code)->first();
        });

        Route::bind('voucherAddress', function ($address) {
            $wallet = \App\Models\Wallet::where([
                'address' => $address,
                'walletable_type' => \App\Models\Voucher::class
            ])->first();

            return $wallet ? $wallet->walletable : $wallet;
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapMunicipalityRoutes();
        
        $this->mapClientRoutes();

        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
        ->namespace($this->namespace)
        ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::domain(env("APP_API_URL"))
        ->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapClientRoutes()
    {
        Route::domain(env("APP_API_URL"))
        ->middleware('client-api')
        ->namespace($this->namespace)
        ->group(base_path('routes/client-api.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapMunicipalityRoutes()
    {
        Route::domain(env("APP_API_URL"))
        ->middleware('municipality-api')
        ->namespace($this->namespace)
        ->group(base_path('routes/municipality-api.php'));
    }
}
