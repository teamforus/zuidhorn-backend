<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

Use App\Models\Role;
Use App\Models\User;
Use App\Models\Buget;
Use App\Models\Shoper;
Use App\Models\Voucher;
Use App\Models\UserBuget;

class BugetController extends Controller
{
    public function putSubmitData(Request $req)
    {
        $data = $req->input('data');
        $categories = $req->input('categories');
        $buget_name = $req->input('buget_name');
        $response = $data;

        $data_map = [
        'bsn_hash'  => array_search('NR. PERS', $data[0]),
        'name'      => array_search('NAAM PERS', $data[0]),
        'buget'     => array_search('BETAALD CRED', $data[0]),
        ];

        $data = array_map(function($row) use ($data_map) {
            $name = explode(' ', $row[$data_map['name']]);

            return [
            "bsn_hash"      => $row[$data_map['bsn_hash']],
            "first_name"    => $name[0],
            "last_name"     => $name[1],
            "buget"         => $row[$data_map['buget']]
            ];
        }, array_slice($data, 1));

        $users = User::generateCitizensByHash($data);

        $shoper_user = Role::where('key', 'shoper')->first()->users->first();
        $buget = Buget::create(['name' => $buget_name]);
        $shoper = Shoper::create([
            'name'      => 'Shoper #' . rand(100000, 999999),
            'user_id'   => $shoper_user->id
            ]);

        $buget->categories()->attach($categories);
        $shoper->categories()->attach($categories);

        $vouchers = $users->map(function($user, $user_key) use ($buget, $data, $shoper) {
            $user_buget = UserBuget::create([
                'buget_id' => $buget->id,
                'user_id' => $user->id,
                'amount' => floatval($data[$user_key]['buget'])
                ]);

            $code = Voucher::generateCode();

            return Voucher::create([
                'code'          => $code,
                'user_buget_id' => $user_buget->id,
                'shoper_id'     => $shoper->id,
                'category_id'   => $shoper->categories->random(1)->first()->id,
                'max_amount'    => floatval($data[$user_key]['buget']),
                ]);
        });

        $export_rows = collect($data)->map(
            function($row, $key) use ($data_map, $vouchers) {
                return [
                $row['bsn_hash'], 
                $vouchers[$key]->code
                ];
            })->toArray();

        $response = array_merge(
            [['BSN_HEX', 'VOUCHER CODE']], 
            $export_rows);

        return compact('response');
    }
}


/*code
user_buget_id
shoper_id
max_amount*/