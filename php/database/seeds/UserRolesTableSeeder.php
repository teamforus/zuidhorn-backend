<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserRolesTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::whereId(1)->first()->roles()->attach(1);
        User::whereId(2)->first()->roles()->attach(4);
        User::whereId(3)->first()->roles()->attach(4);
        User::whereId(4)->first()->roles()->attach(4);
    }
}
