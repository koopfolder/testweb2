<?php

namespace App\Modules\Joinus\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'position_th' => 'required',
            'job_description_th'       => 'required',
            'status'        => 'required',
            'qualifications_th'        => 'required',
        ];
    }
}
