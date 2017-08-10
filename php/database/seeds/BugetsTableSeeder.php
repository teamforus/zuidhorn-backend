<?php

use Illuminate\Database\Seeder;
use App\Models\Buget;

class BugetsTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Buget::create([
            'id'                => 1,
            'name'              => 'Kind Pakket',
            'amount_per_child'  => 300
            ]);
    }
}
