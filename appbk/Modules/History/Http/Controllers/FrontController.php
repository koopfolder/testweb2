<?php

namespace App\Modules\History\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\History\Models\{History};
use Jenssegers\Agent\Agent;

class FrontController extends Controller
{
    public static function getData()
    {
		dd("FrontEndHistory");
        $data = History::FrontData(['publish']);
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
    	return view('template.history',compact('menu'));
    }

}
