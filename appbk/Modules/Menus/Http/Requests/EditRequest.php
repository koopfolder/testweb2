<?php

namespace App\Modules\Menus\Http\Requests;

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
            'name'      => 'required',
            'link_type'    => 'required',
            'status'       => 'required',
            'url_external' => 'required_if:link_type,==,external',
            'layout'       => 'required_if:link_type,==,internal'
        ];
    }
}
