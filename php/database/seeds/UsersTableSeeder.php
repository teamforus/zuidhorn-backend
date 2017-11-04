<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UsersTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id'            => 1,
            'name'          => 'Administrator',
            'email'         => 'forus-admin@dev-weget.nl',
            'password'      => Hash::make('mvp-admin-pass'),
            ]);

        // budget-uploader
        User::create([
            'id'            => 2,
            'name'          => 'Budget Manager',
            'email'         => 'budget-manager@rminds.nl',
            'password'      => Hash::make('budget-manager'),
            ]);

        // budget-manager
        User::create([
            'id'            => 3,
            'name'          => 'Budget Uploader',
            'email'         => 'budget-uploader@rminds.nl',
            'password'      => Hash::make('budget-uploader'),
            ]);

        // shopkeepers-manager
        User::create([
            'id'            => 4,
            'name'          => 'Shopkeeper Manager',
            'email'         => 'shopkeeper-manager@rminds.nl',
            'password'      => Hash::make('shopkeeper-manager'),
            ]);
    }
}