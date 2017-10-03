<?php

namespace App\Http\Requests\Panel;

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
        $states = ShopKeeper::availableStates();

        return [
        'name'              => 'required', 
        'phone'             => 'required', 
        'kvk_number'        => 'required|kvk_number', 
        'btw_number'        => 'required', 
        'iban'              => 'required|iban',
        'email'             => "required|string|email|max:255|unique:users,id,$id",
        'password'          => 'nullable|string|min:6|confirmed',
        'state'             => 'required|in:' . $states->keys()->implode(','),
        'bussines_address'  => 'nullable',
        ];
    }
}
