<?php

namespace App\Http\Requests\App;

use \App\Models\ShopKeeper;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class VoucherSubmitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return ShopKeeper::whereUserId($request->user()->id)->first();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $full_amount = Input::input('full_amount');
        $rules = [];

        $rules['full_amount'] = 'boolean';

        if (!$full_amount)
            $rules['amount'] = 'required|numeric|between:0.1,' . $this->voucher_code->getAvailableFunds();

        return $rules;
    }
}
