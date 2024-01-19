<?php

namespace App\Modules\Activities\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Activities\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Activities\Models\{Article,ArticleRevision};
use Illuminate\Support\Facades\Response;
use RoosterHelpers;
use Junity\Hashids\Facades\Hashids;

class ActivitiesController extends Controller
{
    public static function getData($params)
    {
        $result = array();
        $data =Article::FrontHighlight(['page_layout'=>'activities','page'=>(isset($params['page']) ? $params['page']:'1')]);
        //dd($data);
        return $data;
    }


    public static function getDetail($id){
    	$id = Hashids::decode($id);
        if(collect($id)->isNotEmpty()){
            $data = Article::Detail(['id'=>$id]);
            //dd(collect($data)->isNotEmpty());
            if(collect($data)->isNotEmpty()){
                return view('activities::frontend.activities.index')->with(['data'=>$data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }

    }


}

