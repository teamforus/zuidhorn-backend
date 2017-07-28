<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\User;

class UserPermissionsTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::whereId(1)->first()->permissions()->attach(
            Permission::whereIn('id', [1, 7, 8])->pluck('id')->toArray());
    }
}
