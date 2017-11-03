<?php

namespace App\Http\Controllers\MunicipalityApi;

use App\Models\Budget;
use App\Models\Voucher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BudgetController extends Controller
{
    /**
     * Get budget details.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {
        if (!$request->user()->hasPermission('budget_manage'))
            return response([], 401);
        
        return Budget::first();
    }

    /**
     * Update budget details.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        if (!$request->user()->hasPermission('budget_manage'))
            return response([], 401);

        $this->validate($request, [
            'name'              => 'required|min:2',
            'amount_per_child'  => 'required|min:1'
        ]);

        $budget = Budget::first();
        $budget->update($request->only(['name', 'amount_per_child']));

        return $budget;
    }

    /**
     * Update budget .csv file.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function csv(Request $request) {
        if (!$request->user()->hasPermission('budget_upload'))
            return response([], 401);

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

    /**
     * Get uploaded voucher states.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function voucherState(Request $request) {
        if (!$request->user()->hasPermission('budget_upload'))
            return response([], 401);
        
        $vouchers = Voucher::whereIn('code', $request->input('codes'))->get();

        return $vouchers->keyBy('code')->map(function($voucher) {
            return !is_null($voucher->user_id);
        });

    }
}
