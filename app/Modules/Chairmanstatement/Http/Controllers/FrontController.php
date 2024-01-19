<?php

namespace App\Modules\Chairmanstatement\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Menus\Models\Menu;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Chairmanstatement\Models\{Article};
use Jenssegers\Agent\Agent;

class FrontController extends Controller
{
    public static function getData()
    {
        $data = Article::FrontData("chairman-statement");
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
    	return view('template.chairman_statement',compact('menu'));
    }
}
