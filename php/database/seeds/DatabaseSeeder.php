<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UserRolesTableSeeder::class);
        $this->call(UserPermissionsTableSeeder::class);
        $this->call(ShopKeepersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ShopKeeperCategoriesTableSeeder::class);
        $this->call(BudgetsTableSeeder::class);
        $this->call(BudgetCategoriesTableSeeder::class);
        $this->call(VouchersTableSeeder::class);
        $this->call(VoucherTransactionsTableSeeder::class);
        $this->call(OauthClientsTableSeeder::class);
    }
}
