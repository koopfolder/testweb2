<?php

namespace App\Modules\Chairmanstatement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatecorporategovpolicyRequest extends FormRequest
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
        ];
    }
}