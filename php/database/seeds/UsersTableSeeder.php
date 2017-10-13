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

        User::create([
            'id'            => 2,
            'name'          => 'Budget uploader',
            'email'         => 'budget-uploader@rminds.nl',
            'password'      => Hash::make('budget-uploader'),
            ]);

        User::create([
            'id'            => 3,
            'name'          => 'Budget manager',
            'email'         => 'budget-manager@rminds.nl',
            'password'      => Hash::make('budget-manager'),
            ]);

        User::create([
            'id'            => 4,
            'name'          => 'Shopkeepers manager',
            'email'         => 'shopkeepers-manager@rminds.nl',
            'password'      => Hash::make('shopkeepers-manager'),
            ]);
    }
}