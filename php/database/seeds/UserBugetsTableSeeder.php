<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Buget;
use App\Models\UserBuget;

class UserBugetsTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = Role::where('key', 'citizen')->first()->users;
        $bugets = Buget::get();

        $users->each(function($user) use ($bugets) {
            $user->bugets()->attach(
                $bugets->pluck('id'), 
                ['amount' => 150.50]);
        });
    }
}
