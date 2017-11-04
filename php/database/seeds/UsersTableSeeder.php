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
            'id'            => 3,
            'name'          => 'Budget Uploader',
            'email'         => 'csvvalidator@forus.io',
            'password'      => Hash::make('budget-uploader'),
            ]);

        // budget-manager
        User::create([
            'id'            => 3,
            'name'          => 'Budget Manager',
            'email'         => 'sponsor@forus.io',
            'password'      => Hash::make('budget-manager'),
            ]);

        // shopkeepers-manager
        User::create([
            'id'            => 4,
            'name'          => 'Shopkeeper Manager',
            'email'         => 'shopvalidator@forus.io',
            'password'      => Hash::make('shopkeeper-manager'),
            ]);
    }
}