<?php

namespace App\Modules\Article\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateanticorruptionRequest extends FormRequest
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
            //'attached_file' => 'mimes:xls,xlsx,doc,docx,pdf|max:10240',
            // 'gallery_desktop'  => 'image|mimes:jpeg,png|max:2048',
            'status'        => 'required',
        ];
    }
}
