<?php

namespace App\Modules\Business\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Business\Http\Requests\{CreateRequest};
use App\Modules\Business\Models\{Article};
use Illuminate\Support\Facades\Response;
use RoosterHelpers;

class FrontController extends Controller
{
    public static function getData()
    {
        $data = Article::FrontData(['page_layout'=>'company-business']);
        return $data;
    }


    public static function getViewDetail(){
    	$menu = new \StdClass;
    	$menu->id = '1';
    	$menu->name = trans('business::frontend.business');
    	$menu->description = '';
    	$menu->parent_id = '';
    	$menu->link_type = '';
    	$menu->layout = '';
    	$menu->meta_title = '';
    	$menu->meta_keywords = '';
    	$menu->meta_description = '';
    	return view('template.business',compact('menu'));
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
        return view('template.business',compact('menu'));
    }

}

