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
            'first_name'    => 'Admin',
            'last_name'     => '001',
            'email'         => 'forus-admin@dev-weget.nl',
            'password'      => Hash::make('mvp-admin-pass'),
            ]);

        User::create([
            'id'            => 2,
            'first_name'    => 'ShopKeeper',
            'last_name'     => '001',
            'email'         => 'forus-shop-keeper@weget.nl',
            'password'      => Hash::make('mvp-shop-keeper-pass'),
            ]);

        User::create([
            'id'            => 3,
            'first_name'    => 'Citizen',
            'last_name'     => '001',
            'email'         => 'forus-citizen@weget.nl',
            'password'      => Hash::make('mvp-citizen-pass'),
            ]);
    }
}