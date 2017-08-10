<?php

use Illuminate\Database\Seeder;
use App\Models\UserBuget;
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
        $users = Role::where('key', 'citizen')->first()->users;

        $users->each(function($user) {
            $user->user_bugets->each(function($user_buget) use ($user) {
                $category = $user_buget->buget->categories->random(1)->first();
                $shopKeeper = $category->shop_keepers->random(1)->first();

                Voucher::create([
                    // 'code'           => Voucher::generateCode(),
                    'code'              => 'VIES-2F9M-J8RR-TC5W',
                    'user_buget_id'     => $user_buget->id,
                    'shop_keeper_id'    => $shopKeeper->id,
                    'category_id'       => $category->id,
                    'max_amount'        => null,
                    ]);
            });
        });
    }
}
