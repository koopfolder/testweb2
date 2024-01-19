<?php

namespace App\Modules\Experienceawards\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Experienceawards\Http\Requests\{CreateRequest, EditRequest};
use App\Modules\Article\Models\{Article,ArticleRevision};
use Illuminate\Support\Facades\Response;
use RoosterHelpers;
use Junity\Hashids\Facades\Hashids;

class FrontController extends Controller
{

    public static function getDetail($id){
    	$id = Hashids::decode($id);
        if(collect($id)->isNotEmpty()){
            $data = Article::Detail(['id'=>$id]);
            //dd(collect($data)->isNotEmpty());
            if(collect($data)->isNotEmpty()){
                return view('article::frontend.news.index')->with(['data'=>$data]);
            }else{
                Abort(404);
            }
        }else{
            Abort(404);
        }

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
        return view('template.experience_awards_preview',compact('menu'));

    }


}

