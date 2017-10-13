<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BlockchainApiService\Facades\BlockchainApi;

Use App\Models\Budget;
Use App\Models\Voucher;

class BudgetController extends Controller
{
    public function putSubmitData(Request $req)
    {
        $codes = Voucher::whereNotNull('code')->select('code')->get();
        $codes = $codes->toArray();

        $budget = Budget::where('id', 1)->first();
        $data = collect($req->input('data'));

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
}