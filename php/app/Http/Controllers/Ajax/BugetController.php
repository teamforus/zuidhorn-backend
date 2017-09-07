<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BlockchainApiService\Facades\BlockchainApi;

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
        $users = User::generateCitizens($data);

        if (($sum_childs = $data->sum('count_childs')) == 0)
            throw new Exception("Error Processing Request", 1);

        $amount_per_child = $buget->amount_per_child;

        $vouchers = [];
        $user_bugets = [];

        foreach ($users as $key => $user) {
            $user_bugets[$key] = [
            'buget_id' => $buget->id,
            'user_id' => $user->id,
            'amount' => $data[$key]['count_childs'] * $amount_per_child
            ];
        }

        UserBuget::insert($user_bugets);

        $accounts = collect($users)->map(function($user, $key) use ($user_bugets) {
            return [
                "private" => $user->private_key,
                "funds" => $user_bugets[$key]['amount']
            ]; 
        })->toArray();

        $accounts = collect(BlockchainApi::batchVouchers($accounts)['data'])->toArray();

        $inserted_user_bugets = UserBuget::whereBugetId($buget->id)->doesntHave('vouchers')->get();

        foreach ($inserted_user_bugets as $inserted_user_buget) {
            foreach ($user_bugets as &$user_buget) {
                if ($inserted_user_buget->user_id == $user_buget['user_id'])
                    $user_buget = $inserted_user_buget;
            }
        }

        // $codes = Voucher::pluck('code');

        $voucher_created_date = date('Y-m-d H:i:s', time());

        foreach ($user_bugets as $key => $user_buget) {
            // $code = Voucher::generateCode($codes);

            // $codes->push($code);

            $vouchers[$key] = [
            'code'              => $accounts[$key],
            'private_key'       => $users[$key]->private_key,
            'user_buget_id'     => $user_buget->id,
            'max_amount'        => null,
            'created_at'        => $voucher_created_date
            ];
        }

        Voucher::insert(array_values($vouchers));

        foreach ($data as $key => $data_row) {
            $response[$key] = [
            'id' => $key,
            'code' => $vouchers[$key]['code'],
            'count_childs' => $data[$key]['count_childs'],
            ];
        }

        return compact('response');
    }
}