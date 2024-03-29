<?php

namespace App\Modules\Manager\Http\Requests;

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
            'categories_id' => 'required',
            'name_th'       => 'required',
            'position_th'   =>'required',
            'bord_and_management_type' =>'required',
            'status'        => 'required',
        ];
    }
}
