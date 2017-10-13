<?php

use Illuminate\Database\Seeder;
use App\Models\UserBudget;
use App\Models\Voucher;
use App\Models\ShopKeeper;
use App\Models\Role;

class VouchersTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*$users = Role::where('key', 'citizen')->first()->users;

        $users->each(function($user) {
            $user->user_budgets->each(function($user_budget) use ($user) {

                Voucher::create([
                    // 'code'           => Voucher::generateCode(),
                    'code'              => 'VIES-2F9M-J8RR-TC5W',
                    'user_budget_id'     => $user_budget->id,
                    'max_amount'        => null,
                    ]);
            });
        });*/
    }
}
