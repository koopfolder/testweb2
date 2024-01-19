<?php

namespace App\Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'name'     => 'required|max:150',
            'phone'     => 'required|max:20',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:4,max:60',
            'avatar'   => 'mimes:jpeg,png|max:2048'
        ];
    }
}
