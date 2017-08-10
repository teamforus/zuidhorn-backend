<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ShopKeeper;

class ShopKeeperStoreRequest extends FormRequest
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
        $states = ShopKeeper::availableStates();
        
        return [
        'first_name'        => 'required|string|max:255',
        'last_name'         => 'required|string|max:255',
        'email'             => 'required|string|email|max:255|unique:users',
        'password'          => 'required|string|min:6|confirmed',
        'name'              => 'required',
        'iban'              => 'required',
        'state'             => 'required|in:' . $states->keys()->implode(','),
        'kvk_number'        => 'required',
        'phone_number'      => 'required',
        'bussines_address'  => 'required',
        ];
    }
}
