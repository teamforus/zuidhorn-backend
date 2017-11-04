<?php

namespace App\Http\Requests\Api;

use \Illuminate\Http\Request;
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
    public function rules(Request $request)
    {
        $id = $request->user()->id;

        return [
            'name'          => 'required', 
            'phone'         => 'required', 
            'kvk_number'    => 'required|kvk_number', 
            'iban_name'     => 'required', 
            'email'         => "required|string|email|max:255|unique:users,id,$id"
        ];
    }
}
