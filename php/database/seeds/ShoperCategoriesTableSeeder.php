<?php

use Illuminate\Database\Seeder;
use App\Models\Shoper;
use App\Models\Category;

class ShoperCategoriesTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shoper::get()->each(function($shoper) {
            $shoper->categories()->attach(Category::pluck('id')->toArray());
        });
    }
}
