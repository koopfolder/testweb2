<?php

namespace App\Modules\Experienceawards\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Experienceawards\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Experienceawards\Models\{Article,ArticleRevision};
use Illuminate\Support\Facades\Response;
use RoosterHelpers;
use Junity\Hashids\Facades\Hashids;

class ExperienceawardsController extends Controller
{
    public static function getData($page_layout)
    {
        $result = array();
        $data =Article::FrontDataHighlight(['page_layout'=>$page_layout]);
        //dd($data);
        return $data;
    }

    public static function getDataViewAll($page_layout)
    {
        $result = array();
        $data =Article::FrontData(['page_layout'=>$page_layout]);
        return $data;
    }


    public static function getDatapreview($page_layout)
    {
        $result = array();
        $data =Article::FrontData(['page_layout'=>$page_layout]);
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

    public static function getViewall(){


    //dd('View All');
    return view('experienceawards::viewall.index');
    }
}