<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'id' => 1,
            'key' => 'admin',
            'name' => 'Administrator',
            ]);

        Role::create([
            'id' => 2,
            'key' => 'shop-keeper',
            'name' => 'Shop Keeper',
            ]);

        Role::create([
            'id' => 3,
            'key' => 'citizen',
            'name' => 'Citizen',
            ]);
    }
}
