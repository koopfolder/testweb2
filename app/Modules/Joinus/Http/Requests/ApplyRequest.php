<?php

namespace App\Modules\Joinus\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyRequest extends FormRequest
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
        return[
            'g-recaptcha-response' => 'required',
            'attached_images' => 'image|mimes:jpeg,jpg|max:1024',
            'attachment_history' => 'mimes:pdf|max:1024',
            'other_documents' => 'mimes:pdf|max:1024',
            'other_documents_2' => 'mimes:pdf|max:1024',
            'other_documents_3' => 'mimes:pdf|max:1024',
        ];
    }
}
