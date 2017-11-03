<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ShopKeeper;

class OfficeUpdateRequest extends FormRequest
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
        $schedule_keys = implode(',', range(0, 6));
        
        return [
            'email'         => '',
            'phone'         => '',
            'address'       => 'required',
            'schedules'     => 'required|require_keys:' . $schedule_keys,
            'schedules[*]'  => 'required|schedule',
        ];
    }
}
