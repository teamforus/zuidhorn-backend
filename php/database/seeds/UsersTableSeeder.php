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
            'name'          => 'Buget uploader',
            'email'         => 'buget-uploader@rminds.nl',
            'password'      => Hash::make('buget-uploader'),
            ]);

        User::create([
            'id'            => 3,
            'name'          => 'Buget manager',
            'email'         => 'buget-manager@rminds.nl',
            'password'      => Hash::make('buget-manager'),
            ]);

        User::create([
            'id'            => 4,
            'name'          => 'Shokepers manager',
            'email'         => 'shokepers-manager@rminds.nl',
            'password'      => Hash::make('shokepers-manager'),
            ]);
    }
}