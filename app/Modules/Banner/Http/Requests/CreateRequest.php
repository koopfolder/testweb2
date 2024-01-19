<?php

namespace App\Modules\Banner\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'name' => 'nullable',
            'desktop' => 'image|mimes:jpeg,png|max:2048',
            'mobile' => 'image|mimes:jpeg,png|max:2048',
            'video' => 'mimes:mp4|max:51200',
        ];
    }
}
