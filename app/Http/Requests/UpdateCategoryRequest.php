<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'name' => 'string|unique:subcategories,name,' . $this->request->get('id') . ',id|min:3|max:64',
            'question' => 'string|unique:subcategories,question,' . $this->request->get('id') . ',id|min:3|max:128'
        ];
    }
}
