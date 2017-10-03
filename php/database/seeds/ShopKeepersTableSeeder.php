<?php

use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\User;
use App\Models\ShopKeeper;

class ShopKeepersTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = Role::where('key', 'shop-keeper')->first()->users;
        
        $users->each(function($user) {
            ShopKeeper::create([
                'name'              => 'ShopKeeper #1',
                'user_id'           => $user->id,
                'iban'              => 'MD87MO2259ASV72028867100',
                'kvk_number'        => 'RAND-43598327523',
                'bussines_address'  => 'Netherlands, Rand Dist, str. Rand 32/64',
                'phone'             => '843537264578324',
                'state'             => 'pending',
                ]);
        });
    }
}
