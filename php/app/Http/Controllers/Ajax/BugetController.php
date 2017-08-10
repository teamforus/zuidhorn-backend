<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

Use App\Models\Role;
Use App\Models\User;
Use App\Models\Buget;
Use App\Models\ShopKeeper;
Use App\Models\Voucher;
Use App\Models\Category;
Use App\Models\UserBuget;

class BugetController extends Controller
{
    public function putSubmitData(Request $req)
    {
        $response = collect();

        $data = collect($req->input('data'));

        $buget = Buget::where('id', 1)->first();
        $shopKeeper = ShopKeeper::where('id', 1)->first();
        $category = Category::where('id', 1)->first();

        $users = User::generateCitizens($data);

        if (($sum_childs = $data->sum('count_childs')) == 0)
            throw new Exception("Error Processing Request", 1);

        $amount_per_child = $buget->amount_per_child;

        $vouchers = collect();

        foreach ($users as $key => $user) {
            $user_buget = UserBuget::create([
                'buget_id' => $buget->id,
                'user_id' => $user->id,
                'amount' => $data[$key]['count_childs'] * $amount_per_child
                ]);

            $code = Voucher::generateCode();

            $vouchers[$key] = Voucher::create([
                'code'              => $code,
                'user_buget_id'     => $user_buget->id,
                'shop_keeper_id'    => $shopKeeper->id,
                'category_id'       => $category->id,
                'max_amount'        => null,
                ]);
        }

        foreach ($data as $key => $data_row) {
            $response[$key] = [
            'id' => $key,
            'code' => $vouchers[$key]->code,
            'count_childs' => $data[$key]['count_childs'],
            ];
        }

        return compact('response');
    }
}