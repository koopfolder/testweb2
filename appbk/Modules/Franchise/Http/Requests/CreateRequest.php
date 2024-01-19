<?php

namespace App\Modules\Franchise\Http\Requests;

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
        $rules = [
            'juristic_person_registration_number' => 'required',
            'brand_name'  => 'required',
            'company_name' => 'required',
            'lat'  => 'required',
            'lng'  => 'required',
            'contact_subdistrict' => 'required',
            'contact_district' => 'required',
            'contact_province' => 'required',
            'contact_zipcode' => 'required',
            'cover_desktop' => 'image|mimes:jpeg,png|max:2048',
            'cover_logo'  => 'image|mimes:jpeg,png|max:2048',
            'file_branch'  => 'mimes:xlsx|max:5048',
            'status'  => 'required',
        ];
    

        $nbr = count($this->input('gallery_desktop')) - 1;
        foreach(range(0, $nbr) as $index) {
            $rules['gallery_desktop.' . $index] = 'image|mimes:jpeg,png|max:2048';
        }

        $nbr_doc = count($this->input('document')) - 1;
        foreach(range(0, $nbr_doc) as $index) {
            $rules['document.' . $index] = 'mimes:xlsx,xls,doc,docx,pdf,zip|max:5048';
        }

        if($nbr_doc > 0){
            $nbr = count($this->input('document_name')) - 1;
            foreach(range(0, $nbr) as $index) {
                $rules['document_name.' . $index] = 'required';
            }
        }
        
        return $rules;
    }
}
