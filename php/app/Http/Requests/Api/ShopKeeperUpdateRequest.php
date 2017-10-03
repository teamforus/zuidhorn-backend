<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ShopKeeper;

class ShopKeeperUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('shop_keeper')->id;

        return [
            'name'          => 'required', 
            'phone'         => 'required', 
            'kvk_number'    => 'required|kvk_number', 
            'btw_number'    => 'required', 
            'iban'          => 'required|iban',
            'email'         => "required|string|email|max:255|unique:users,id,$id"
        ];
    }
}
