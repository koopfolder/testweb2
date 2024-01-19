<?php

namespace App\Modules\Organization\Http\Requests;

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
            'desktop_th' => 'required_if:check_desktop_th,==,0|image|mimes:jpeg,png|max:2048',
            'mobile_th'  => 'required_if:check_mobile_th,==,0|image|mimes:jpeg,png|max:2048',
            'desktop_en' => 'image|mimes:jpeg,png|max:2048',
            'mobile_en'  => 'image|mimes:jpeg,png|max:2048',
        ];
    }
}
