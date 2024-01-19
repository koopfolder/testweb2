<?php

namespace App\Modules\Article\Http\Requests;

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
        $rules = [
            'title' => 'required',
            'description'       => 'required',
            'short_description'       => 'required',
            'status'        => 'required',
            'cover_desktop' => 'image|mimes:jpeg,png|max:5048',
            'cover_mobile'  => 'image|mimes:jpeg,png|max:5048',
            'start_date'        =>'required',
            //'start_date'      =>'required|before_or_equal:end_date',
            //'end_date'        => 'after_or_equal:start_date',
           // 'gallery_desktop'  => 'image|mimes:jpeg,png|max:5048',
            'status'        => 'required',
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
