<?php

namespace App\Modules\Sustainability\Http\Requests;

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
            'title_th' => 'required',
            'description_th'       => 'required',
            'status'        => 'required',
            'cover_desktop' => 'image|mimes:jpeg,png|max:2048',
            'cover_mobile'  => 'image|mimes:jpeg,png|max:2048',
           // 'gallery_desktop'  => 'image|mimes:jpeg,png|max:2048',
            'status'        => 'required',
        ];
    }
}
