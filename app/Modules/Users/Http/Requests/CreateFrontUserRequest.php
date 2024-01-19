<?php

namespace App\Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFrontUserRequest extends FormRequest
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
            'firstname'     => 'required|max:50',
            'lastname'     => 'required|max:50',
            'phone'     => 'required|max:20',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:4,max:60',
            'date_of_birth'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'username.unique' => 'username_unique',
            'email.unique' => 'email_unique',
        ];
    }
}
