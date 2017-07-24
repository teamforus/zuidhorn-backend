<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'id'        => 1,
            'name'      => 'Transport',
            'parent_id' => null,
            ]);

        Category::create([
            'id'        => 2,
            'name'      => 'Cars',
            'parent_id' => 1,
            ]);

        Category::create([
            'id'        => 3,
            'name'      => 'Bikes',
            'parent_id' => 1,
            ]);

        Category::create([
            'id'        => 4,
            'name'      => 'Moto',
            'parent_id' => 1,
            ]);
    }
}
