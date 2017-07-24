<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category;

class CategoryEditRequest extends FormRequest
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
        $allowed_parents = array_keys(Category::hierarchicalSelectOptions());

        return [
        'name'      => 'required|between:2,300',
        'parent_id' => 'in:' . collect($allowed_parents)->implode(',')
        ];
    }
}
