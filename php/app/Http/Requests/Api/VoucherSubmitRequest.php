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
        $max_amount = $this->voucher_public_key->getAvailableFunds();

        $rules = [
            'full_amount'   => 'boolean',
            'extra_amount'  => 'nullable|numeric',
        ];

        if (!$full_amount) 
            $rules['amount'] = "required|numeric|between:0.1,$max_amount";
        

        return $rules;
    }
}
