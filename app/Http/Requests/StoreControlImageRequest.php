<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreControlImageRequest extends FormRequest
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
            'category_id' => 'exists:categories,id',
            'images' => 'required',
            'images.*' => 'file|image|mimes:jpeg,png,bmp,gif,svg|max:9999'
        ];
    }
}
