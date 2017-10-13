<?php

use Illuminate\Database\Seeder;
use App\Models\Budget;
use App\Models\Category;
use App\Models\BudgetCategory;

class BudgetCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Budget::get()->each(function($budget) { 
            $budget->categories()->attach(Category::pluck('id')->toArray());
        });
    }
}
