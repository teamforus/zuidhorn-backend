<?php

namespace App\Http\Controllers\MunicipalityApi;

use App\Models\Buget;
use App\Models\Voucher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BugetController extends Controller
{
    public function get(Request $request) {
        return Buget::first();
    }

    public function update(Request $request) {
        $this->validate($request, [
            'name' => 'required|min:2',
            'amount_per_child' => 'required|min:1'
        ]);

        $buget = Buget::first();
        $buget->update($request->only(['name', 'amount_per_child']));

        return $buget;
    }

    public function csv(Request $request) {
        $codes = Voucher::whereNotNull('code')->select('code')->get();
        $codes = $codes->toArray();

        $buget = Buget::where('id', 1)->first();
        $data = collect($request->all());

        $vouchers = $data->map(function($row) use ($codes, $buget) {
            $code = Voucher::generateUid($codes, 'code', 4, 2);
            array_push($codes, $code);

            return [
            'code'          => $code,
            'buget_id'      => $buget->id,
            'user_id'       => null,
            'amount'        => $row['count_childs'] * $buget->amount_per_child,
            'created_at'    => date('Y-m-d H:i:s', time())
            ];
        });

        $response = $data->map(function($row, $key) use ($vouchers) {
            return [
            'id'                => $key,
            'code'              => $vouchers[$key]['code'],
            'count_childs'      => $row['count_childs'],
            ];
        })->toArray();

        Voucher::insert($vouchers->toArray());

        return compact('response');
    }
}
