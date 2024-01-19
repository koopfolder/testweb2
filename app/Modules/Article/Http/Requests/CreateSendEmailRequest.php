<?php

namespace App\Modules\Article\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSendEmailRequest extends FormRequest
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
            'subject' => 'required',
            'description'       => 'required',
            'to'       => 'required',
        ];

        // $nbr = count($this->input('gallery_desktop')) - 1;
        // foreach(range(0, $nbr) as $index) {
        //     $rules['gallery_desktop.' . $index] = 'image|mimes:jpeg,png|max:5048';
        // }

        // $nbr_doc = count($this->input('document')) - 1;
        // foreach(range(0, $nbr_doc) as $index) {
        //     $rules['document.' . $index] = 'mimes:jpeg,png,xlsx,xls,doc,docx,pdf,zip|max:52400';
        // }

        // if($nbr_doc > 0){
        //     $nbr = count($this->input('document_name')) - 1;
        //     foreach(range(0, $nbr) as $index) {
        //         $rules['document_name.' . $index] = 'required';
        //     }
        // }

        return $rules;
    }
}
