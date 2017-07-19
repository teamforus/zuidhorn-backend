<?php

use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\User;
use App\Models\Shoper;

class ShopersTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = Role::where('key', 'shoper')->first()->users;
        
        $users->each(function($user) {
            Shoper::create(['user_id' => $user->id]);
        });
    }
}
