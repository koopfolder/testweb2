<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Logs;
use App\Module;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public static function escape(){
    	return \DB::connection()->getPdo();
    }


    public static function postLogs($params){
    	//dd($params,auth()->user()->name,auth()->user()->id);
    	$data = array();
    	$data['event'] = auth()->user()->name." ".$params['event'];
    	$data['module_id'] = $params['module_id'];
    	$data['created_by'] = auth()->user()->id;

    	Logs::create($data);
    	return true;
    }

	public function removeTagHTML($str = "")
    {
        $str = strip_tags($str);
        $str = preg_replace("/\r\n/i","",$str);
        $str = preg_replace("/\n/i", "",$str);
        $str = preg_replace("/\r|\n/","",$str);
        $str = preg_replace( "/<br>|\n/", "", $str );
		$str = preg_replace("/\[(.*?)\]\s*(.*?)\s*\[\/(.*?)\]/", "[$1]$2[/$3]", html_entity_decode($str));
        /*$str = trim(preg_replace('/\s\s+/', ' ', $str));*/
        return $str;
    }

}
