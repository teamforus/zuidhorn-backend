<?php

use Illuminate\Database\Seeder;
use App\Models\ShopKeeper;
use App\Models\Category;

class ShopKeeperCategoriesTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShopKeeper::get()->each(function($shopKeeper) {
            $shopKeeper->categories()->attach(Category::pluck('id')->toArray());
        });
    }
}
