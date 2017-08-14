<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AuthSignUpRequest extends FormRequest
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
        return [
            'kvk_number'    => 'required',
            'iban'          => 'required',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:6',
        ];
    }
}
