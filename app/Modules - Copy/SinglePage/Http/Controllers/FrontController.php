<?php

namespace App\Modules\SinglePage\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\SinglePage\Models\{SinglePage,SinglePageHitLogs};
use Jenssegers\Agent\Agent;
use Junity\Hashids\Facades\Hashids;
use DB;

class FrontController extends Controller
{
    public static function getData($params)
    {
		$id =   ($params->layout_params !='' ?json_decode($params->layout_params)->id:'hap');
		$data = SinglePage::FrontData(['id'=>$id]);


        $token= csrf_token();
        //     //dd($token);
        $check =  SinglePageHitLogs::DataID(['token'=>$token,'id'=>$data->id]);
        if(!isset($check->id)){
            SinglePageHitLogs::create(['token'=>$token,'single_page_id'=>$data->id]);
            SinglePage::where('id','=',$data->id)->update(['hit'=>DB::raw('hit+1')]);
            $data = SinglePage::FrontData(['id'=>$id]);
        }

		//dd($id,$data,$token);
        return $data;
	}
	
    public static function getPreview($id){
		$id = Hashids::decode($id);
        //dd($id);
		$data = SinglePage::FrontData(['id'=>$id]);
		//dd($data,$id);
        return view('single-page::frontend.index', compact('data'));
	}


    public static function getFrontend($id){
        $id = Hashids::decode($id);
        //dd($id);
        $data = SinglePage::FrontData(['id'=>$id]);
        //dd($data,$id);
        
        $token= csrf_token();
        //     //dd($token);
        $check =  SinglePageHitLogs::DataID(['token'=>$token,'id'=>$data->id]);
        if(!isset($check->id)){
            SinglePageHitLogs::create(['token'=>$token,'single_page_id'=>$data->id]);
            SinglePage::where('id','=',$data->id)->update(['hit'=>DB::raw('hit+1')]);
            $data = SinglePage::FrontData(['id'=>$id]);
        }
        return view('single-page::frontend.index', compact('data'));
    }
	
    public static function getViewCaseBanner($id){
		$id = Hashids::decode($id);
		$data = SinglePage::FrontData(['id'=>$id]);
		//dd($data,$id);
        return view('single-page::frontend.index', compact('data'));
    }



}
