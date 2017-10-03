<?php

namespace App\Http\Requests\ClientApi;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
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
            'first_name'    => 'required',
            'last_name'     => 'required',
            'subject'       => 'required',
            'email'         => 'required|email',
            'phone'         => '',
            'message'       => 'required',
        ];
    }
}
