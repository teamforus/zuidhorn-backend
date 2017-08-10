<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\VoucherTransaction::class => \App\Policies\VoucherTransactionPolicy::class,
        \App\Models\Category::class => \App\Policies\CategoryPolicy::class,
        \App\Models\Voucher::class => \App\Policies\VoucherPolicy::class,
        \App\Models\ShopKeeper::class => \App\Policies\ShopKeeperPolicy::class,
        \App\Models\ShopKeeperCategory::class => \App\Policies\ShopKeeperCategoryPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Buget::class => \App\Policies\BugetPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('upload_bugets', function ($user) {
            return $user->permissions()->where('key', 'upload_bugets')->count();
        });

        Gate::define('manage_categories', function ($user) {
            return $user->permissions()->where('key', 'manage_categories')->count();
        });

        Gate::define('manage_citizens', function ($user) {
            return $user->permissions()->where('key', 'manage_citizens')->count();
        });

        Gate::define('manage_shop-keepers', function ($user) {
            return $user->permissions()->where('key', 'manage_shop-keepers')->count();
        });

        Gate::define('manage_bugets', function ($user) {
            return $user->permissions()->where('key', 'manage_bugets')->count();
        });

        Gate::define('manage_vouchers', function ($user) {
            return $user->permissions()->where('key', 'manage_vouchers')->count();
        });

        Gate::define('manage_admins', function ($user) {
            return $user->permissions()->where('key', 'manage_admins')->count();
        });

        Gate::define('manage_permissions', function ($user) {
            return $user->permissions()->where('key', 'manage_permissions')->count();
        });

        Gate::define('manage_vouchers_transactions', function ($user) {
            return $user->permissions()->where('key', 'manage_vouchers_transactions')->count();
        });
    }
}
