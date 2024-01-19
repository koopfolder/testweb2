<?php

namespace App\Modules\Documentsdownload\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use RoosterHelpers;
class EditRequest extends FormRequest
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
        $mimes = RoosterHelpers::getMimes();
        return [
            'title_th'         => 'required',
            'attached_file_th' => 'mimes:'.$mimes.'|max:10240',
            'attached_file_en' => 'mimes:'.$mimes.'|max:10240',
            'cover_desktop' => 'image|mimes:jpeg,png|max:2048',
            'cover_mobile'  => 'image|mimes:jpeg,png|max:2048',
            'status'       => 'required',
        ];
    }
}
