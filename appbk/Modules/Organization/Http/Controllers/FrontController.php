<?php

namespace App\Modules\Organization\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Organization\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Organization\Models\{Organization};
use RoosterHelpers;

class FrontController extends Controller
{
    public static function getData()
    {
        //$data = History::FrontData(['publish']);
        $data = Organization::where('id','=','1')->first();
        //dd($data);
        return $data;
    }

    public static function getPreview(){
    	$menu = new \StdClass;
    	$menu->id = '1';
    	$menu->name = 'Preview';
    	$menu->description = 'Preview';
    	$menu->parent_id = 'Preview';
    	$menu->link_type = 'Preview';
    	$menu->layout = 'Preview';
    	$menu->meta_title = 'Preview';
    	$menu->meta_keywords = 'Preview';
    	$menu->meta_description = 'Preview';
    	return view('template.organization_chart',compact('menu'));
    }

}
