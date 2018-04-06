<?php

namespace App\Http\Controllers\MunicipalityApi;
;
use App\Models\Budget;
use App\Models\Voucher;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BudgetController extends Controller
{
    /**
     * Get budget details.
     * @param Request $request
     * @return \Illuminate\Http\Response|Budget
     */
    public function show(Request $request) {
        if (!$request->user()->hasPermission('budget_manage')) {
            return response([], 401);
        }

        $budgetModel = new Budget();
        
        return $budgetModel->first();
    }

    /**
     * Update budget details.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response|Budget
     */
    public function update(Request $request) {
        if (!$request->user()->hasPermission('budget_manage')) {
            return response([], 401);
        }

        $this->validate($request, [
            'name'              => 'required|min:2',
            'amount_per_child'  => 'required|min:1'
        ]);

        $budgetModel = new Budget();

        $budget = $budgetModel->first();
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
        if (!$request->user()->hasPermission('budget_upload')) {
            return response([], 401);
        }

        $budgetModel = new Budget();
        $voucherModel = new Voucher();

        $codes = $voucherModel->whereNotNull('code')->select('code')->get();
        $codes = $codes->toArray();

        $budget = $budgetModel->where('id', 1)->first();
        $data = collect($request->all());

        $vouchers = $data->map(function($row) use ($codes, $budget) {
            $code = Voucher::generateUid($codes, 'code', 4, 2);
            array_push($codes, $code);

            return [
                'code'          => $code,
                'budget_id'     => $budget->id,
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

        $voucherModel->insert($vouchers->toArray());

        return compact('response');
    }

    /**
     * Add child to target voucher by activation code
     * @param Request $request
     * @return array
     */
    public function addChildren(Request $request) {
        $budgetModel = new Budget();
        $voucherModel = new Voucher();

        $targetVoucher = $voucherModel->where([
            'code' => $request->input('code', '')
        ])->first();

        $budget = $budgetModel->where('id', 1)->first();

        $targetVoucher->amount += $budget->amount_per_child;
        $targetVoucher->save();

        if (!is_null($targetVoucher->user) && !is_null($targetVoucher->wallet)) {
            $targetVoucher->wallet->fundTokens($budget->amount_per_child);
        }

        return [
            "success" => true
        ];
    }

    /**
     * Get uploaded voucher states.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response|Collection
     */
    public function voucherState(Request $request) {
        if (!$request->user()->hasPermission('budget_upload')) {
            return response([], 401);
        }

        $voucherModel = new Voucher();
        
        $vouchers = $voucherModel->whereIn('code', $request->input('codes'))->get();

        return $vouchers->keyBy('code')->map(function($voucher) {
            return !is_null($voucher->user_id);
        });

    }
}
