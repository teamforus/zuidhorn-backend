<?php

use Illuminate\Database\Seeder;
use App\Models\Budget;

class BudgetsTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Budget::create([
            'id'                => 1,
            'name'              => 'Kind Pakket',
            'amount_per_child'  => 300
            ]);
    }
}
