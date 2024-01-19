<?php

namespace App\Modules\Article\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateHealthRequest extends FormRequest
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
        $rules = [
            'title' => 'required',
            'status'        => 'required',
            'cover_desktop' => 'image|mimes:jpeg,png|max:5048',
            //'start_date'      =>'required|before_or_equal:end_date',
            //'end_date'        => 'after_or_equal:start_date',
           // 'gallery_desktop'  => 'image|mimes:jpeg,png|max:5048',
            'status'        => 'required',
        ];

        return $rules;
    }
}
