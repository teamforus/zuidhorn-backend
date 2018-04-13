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
        (new User())->create([
            'id'            => 1,
            'name'          => 'Administrator',
            'email'         => 'forus-admin@dev-weget.nl',
            'password'      => app('hash')->make('mvp-admin-pass'),
            ]);

        // budget-uploader
        (new User())->create([
            'id'            => 2,
            'name'          => 'Budget Uploader',
            'email'         => 'csvvalidator@forus.io',
            'password'      => app('hash')->make('budget-uploader'),
            ]);

        // budget-manager
        (new User())->create([
            'id'            => 3,
            'name'          => 'Budget Manager',
            'email'         => 'sponsor@forus.io',
            'password'      => app('hash')->make('budget-manager'),
            ]);

        // shopkeepers-manager
        (new User())->create([
            'id'            => 4,
            'name'          => 'Shopkeeper Manager',
            'email'         => 'shopvalidator@forus.io',
            'password'      => app('hash')->make('shopkeeper-manager'),
            ]);
    }
}