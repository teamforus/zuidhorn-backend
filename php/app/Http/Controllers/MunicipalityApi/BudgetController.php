<?php

namespace App\Http\Controllers\MunicipalityApi;

use App\Models\Budget;
use App\Models\Voucher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BudgetController extends Controller
{
    public function get(Request $request) {
        return Budget::first();
    }

    public function update(Request $request) {
        $this->validate($request, [
            'name' => 'required|min:2',
            'amount_per_child' => 'required|min:1'
        ]);

        $budget = Budget::first();
        $budget->update($request->only(['name', 'amount_per_child']));

        return $budget;
    }

    public function csv(Request $request) {
        $codes = Voucher::whereNotNull('code')->select('code')->get();
        $codes = $codes->toArray();

        $budget = Budget::where('id', 1)->first();
        $data = collect($request->all());

        $vouchers = $data->map(function($row) use ($codes, $budget) {
            $code = Voucher::generateUid($codes, 'code', 4, 2);
            array_push($codes, $code);

            return [
            'code'          => $code,
            'budget_id'      => $budget->id,
            'user_id'       => null,
            'amount'        => $row['count_childs'] * $budget->amount_per_child,
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

    public function voucherState(Request $request) {
        $vouchers = Voucher::whereIn('code', $request->input('codes'))->get();

        return $vouchers->keyBy('code')->map(function($voucher) {
            return !is_null($voucher->user_id);
        });

    }
}
