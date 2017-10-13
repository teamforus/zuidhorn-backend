<?php

namespace App\Providers;

use Laravel\Passport\Passport;

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
        \App\Models\Transaction::class => \App\Policies\TransactionPolicy::class,
        \App\Models\Category::class => \App\Policies\CategoryPolicy::class,
        \App\Models\Voucher::class => \App\Policies\VoucherPolicy::class,
        \App\Models\ShopKeeper::class => \App\Policies\ShopKeeperPolicy::class,
        \App\Models\ShopKeeperCategory::class => \App\Policies\ShopKeeperCategoryPolicy::class,
        \App\Models\Office::class => \App\Policies\OfficePolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Budget::class => \App\Policies\BudgetPolicy::class,
        \App\Models\BudgetCategory::class => \App\Policies\BudgetCategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes(null, [
            'prefix' => 'api/oauth'
            ]);

        Passport::routes(null, [
            'prefix' => 'municipality/oauth'
            ]);

        Gate::define('upload_budgets', function ($user) {
            return $user->permissions()->where('key', 'upload_budgets')->count();
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

        Gate::define('manage_budgets', function ($user) {
            return $user->permissions()->where('key', 'manage_budgets')->count();
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
