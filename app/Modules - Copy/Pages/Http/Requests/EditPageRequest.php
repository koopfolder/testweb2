<?php

namespace App\Modules\Pages\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditPageRequest extends FormRequest
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
            'title'   => 'required',
            'image'   => 'mimes:jpeg,bmp,png|max:2048',
            'mobile' => 'mimes:jpeg,bmp,png|max:2048',
            'file'    => 'mimes:pdf|max:2048'
        ];
    }
}
