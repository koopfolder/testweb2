<?php

namespace App\Modules\Exhibition\Http\Requests;

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
            'title'         => 'required',
            'attached_file' => 'mimes:zip|max:10240',
            'cover_desktop' => 'image|mimes:jpeg,png|max:5048',
            'start_date'    => 'required',
            //'start_date'    => 'required|before_or_equal:end_date',
            //'end_date'      => 'required|after_or_equal:start_date',
            'status'       => 'required',
        ];
    }
}
