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
            Permission::whereIn('id', range(4, 11))->pluck('id'));

        User::whereId(2)->first()->permissions()->attach(
            Permission::whereIn('key', ['buget_upload'])->pluck('id'));

        User::whereId(3)->first()->permissions()->attach(
            Permission::whereIn('key', ['buget_manage'])->pluck('id'));

        User::whereId(4)->first()->permissions()->attach(
            Permission::whereIn('key', ['shopkeeper_manage'])->pluck('id'));
    }
}
