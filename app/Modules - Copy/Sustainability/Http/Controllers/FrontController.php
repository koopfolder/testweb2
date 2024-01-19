<?php

namespace App\Modules\Sustainability\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Sustainability\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Sustainability\Models\{Article};
use Illuminate\Support\Facades\Response;
use RoosterHelpers;

class FrontController extends Controller
{
    public static function getData()
    {
        $data = Article::FrontData(['page_layout'=>'corporate-gov-policy']);  
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
    	return view('template.corporate_governance_policy',compact('menu'));
    }
}

