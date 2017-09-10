<?php

namespace App\Providers;

use App\Models\ShopKeeperDevice;
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
        
        Route::model('shopKeeper', \App\Models\ShopKeeper::class);
        Route::model('shopKeeperCategory', \App\Models\ShopKeeperCategory::class);
        
        Route::model('office', \App\Models\ShopKeeperOffice::class);
        Route::model('buget', \App\Models\Buget::class);
        Route::model('bugetCategory', \App\Models\BugetCategory::class);
        
        Route::model('voucher_transaction', \App\Models\VoucherTransaction::class);
        Route::model('user', \App\Models\User::class);

        Route::bind('device_approve_token', function ($token) {
            return \App\Models\ShopKeeperDevice::whereApproveToken($token)->first();
        });

        Route::bind('voucher_code', function ($code) {
            return \App\Models\Voucher::whereCode($code)->first();
        });

        Route::bind('voucher_public_key', function ($public_key) {
            return \App\Models\Voucher::wherePublicKey($public_key)->first();
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
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
        Route::prefix('api')
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
        Route::prefix('client')
        ->middleware('client-api')
        ->namespace($this->namespace)
        ->group(base_path('routes/client-api.php'));
    }
}
