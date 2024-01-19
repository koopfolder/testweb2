<?php

namespace App\Modules\Documentsdownload\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Documentsdownload\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Documentsdownload\Models\{Documents};
use App\Modules\Api\Models\{ListMedia};
use Junity\Hashids\Facades\Hashids;

class DocumentController extends Controller
{
   
    public function getFlipbook($id)
    {
        $id = Hashids::decode($id);
        $item = ListMedia::FrontDataId(['id'=>$id]);
        $json = (isset($item->json_data) !='' ? json_decode($item->json_data):'');
        $path_file = (isset($json->FileAddress) ? $json->FileAddress:'');

        //dd($json,$path_file);

        return view('template.pdf_flipbook',compact('path_file','item'));

    }
            
}
