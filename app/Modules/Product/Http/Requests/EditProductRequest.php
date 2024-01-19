<?php

namespace App\Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditProductRequest extends FormRequest
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
            'title'           => 'required',
            'desktop'         => 'mimes:jpeg,bmp,png|max:2048',
            'mobile'          => 'mimes:jpeg,bmp,png|max:2048',
            'start_published' => 'nullable|date',
            'end_published'   => 'nullable|date'
        ];
    }
}
