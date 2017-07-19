<?php

use Illuminate\Database\Seeder;
use App\Models\Buget;
use App\Models\Category;
use App\Models\BugetCategory;

class BugetCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Buget::get()->each(function($buget) { 
            $buget->categories()->attach(Category::pluck('id')->toArray());
        });
    }
}
