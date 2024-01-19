<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use DB;

class ListArticle extends Model implements HasMediaConversions
{
    use HasMediaTrait;
    protected $table = 'list_article';
    protected $primaryKey = 'id';

    protected $fillable = [
        'article_id',
        'UploadFileID',
        'json_data',
        'title',
        'description',
        'featured',
        'status',
        'created_at',
        'updated_at', 
        'created_by',
        'updated_by',
        'knowledges',
        'media_campaign',
        'hit',
        'slug',
        'article_type',
        'sex',
        'age',
        'tags',
        'web_view',
        'image_path'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function getLang(){
        return \App::getLocale();
    }


    public function scopeData($query,$params)
    {

        $query->select('id','title','recommend','articles_research','include_statistics','notable_books','interesting_issues','health_literacy','featured','created_at','updated_at','created_by','updated_by','status');
        return $query->whereIn('status',$params['status'])
                    ->where('title','!=','')
                    ->orderBy('created_at','DESC')
                    //->toSql();
                    ->get();
    }


    public function scopeDataDropDown($query,$params)
    {

        $query->select('id','title');
        return $query->whereIn('status',$params['status'])
            ->whereRaw('title !=""')
            ->orderBy('created_at','DESC')
            //->toSql();
            ->get();
    }

    public function scopeFrontData($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();

        $query->select('id','title','description','interesting_issues','json_data','featured','created_at','created_by','updated_by','status','updated_at');
        if($params['featured'] ===true){
                $query->where('featured','=',2);
        }else{
                //$query->where('featured','!=',2);
        }
        if($params['interesting_issues'] ===true){
            $query->where('interesting_issues','=',2);
        }else{
                //$query->where('featured','!=',2);
        }
        if($params['retrieving_results'] =='first'){
            return $query->whereIn('status',$params['status'])
                     ->with('createdBy','updatedBy')
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     ->first();
        }else{
            return $query->whereIn('status',$params['status'])
                     ->with('createdBy','updatedBy')
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        }

    }


    public function scopeFrontDataInterestingIssuesNews($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();

        $query->select('id','title','description','json_data');
        if($params['featured'] ===true){
                $query->where('featured','=',2);
        }else{
                //$query->where('featured','!=',2);
        }
        if($params['interesting_issues'] ===true){
            $query->where('interesting_issues','=',2);
        }else{
                //$query->where('featured','!=',2);
        }
        if($params['retrieving_results'] =='first'){
            return $query->whereIn('status',$params['status'])
                     ->limit($params['limit'])
                     ->orderByRaw('updated_at DESC')
                     ->first();
        }else{
            return $query->whereIn('status',$params['status'])
                     ->limit($params['limit'])
                     ->orderByRaw('updated_at DESC')
                     ->get();

        }

    }


    public function scopeFrontDataArticlesResearch($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();
        $query->select('id','title','description','json_data')->join('list_category','Article.category_id', '=', 'list_category.category_id');

        if($params['featured'] ===true){
                $query->where('featured','=',2);
        }else{
                //$query->where('featured','!=',2);
        }

        if($params['articles_research'] ===true){
            $query->where('articles_research','=',2);
        }else{
                //$query->where('featured','!=',2);
        }
        if($params['retrieving_results'] =='first'){
            return $query->whereIn('status',$params['status'])
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     ->first();
        }else{
            return $query->whereIn('status',$params['status'])
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        }

    }


    public function scopeFrontDataIncludeStatistics($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();

        $query->select('id','title','description','json_data');

        if($params['featured'] ===true){
                $query->where('featured','=',2);
        }else{
                //$query->where('featured','!=',2);
        }

        if($params['include_statistics'] ===true){
            $query->where('include_statistics','=',2);
        }else{
                //$query->where('featured','!=',2);
        }
        if($params['retrieving_results'] =='first'){
            return $query->whereIn('status',$params['status'])
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     ->first();
        }else{
            return $query->whereIn('status',$params['status'])
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        }

    }



    public function scopeFrontList($query,$params){

        //$result = array();
        //dd($params);
        $query->select('id','title','description','interesting_issues','hit','json_data','featured','created_at','created_by','updated_by','status','updated_at');
        $query->where('status','=','publish');
        //$query->where('featured','=',"1");
        $query->orderByRaw('featured,created_at DESC');
        $data = $query->paginate(7);
        //$result['items']= $data;
        return $data;

    }


    public function scopeFrontListJsonSearch($query,$params){

        //$result = array();
        //dd($params);
        //$query->select('id');
        $query->select(
            'list_article.article_id AS id',
            'list_article.title',
            'list_article.description',
            'list_article.hit',
            'list_article.json_data',
            'list_article.created_at',
            'list_article.template',
            'list_article.article_type',
            'list_article.slug',
            'list_article.tags',
            'list_media.category_id'
        );
        
        //$query->where('featured','=',"1");

        $query->leftjoin('list_media', 'list_article.article_id', '=', 'list_media.id')
            ->where('list_media.status', '=', 'publish')
            ->wherenull('list_media.not_show_dol')->whereNull('list_media.media_trash')
            ->whereIn('list_media.show_rc',[0,2])
            ->where(function ($list_media) {
                $list_media->where('list_media.start_date','<=',date("Y-m-d"))
                    ->orWhereNull('list_media.start_date');
            })
            ->where(function ($list_media) {
                $list_media->where('list_media.end_date','>=',date("Y-m-d"))
                    ->orWhereNull('list_media.end_date');
            });

        if(isset($params['params']['issue']) && $params['params']['issue'] !="0"){
            //$query->where('issues_id', '=',$params['params']['issue']);
            $issues_condition = '{"ID":'.$params['params']['issue'].'}';
            $issues_field = '$.Issues';
            $query->whereRaw('JSON_CONTAINS (list_article.json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')');
        }

        if(isset($params['params']['template']) && $params['params']['template'] !="0"){
            $query->where('list_article.template', '=',$params['params']['template']);
        }

        if(isset($params['params']['target']) && $params['params']['target'] !="0"){
            //$query->where('target_id', '=',$params['params']['target']);
            $target_condition = '{"ID":'.$params['params']['target'].'}';
            $target_field = '$.Targets';
            $query->whereRaw('JSON_CONTAINS (list_article.json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
        }

        if(isset($params['params']['target_age']) && $params['params']['target_age'] !="0"){
            //$query->where('target_id', '=',$params['params']['target']);
            $target_condition = '{"ID":'.$params['params']['target_age'].'}';
            $target_field = '$.Targets';
            $query->whereRaw('JSON_CONTAINS (list_article.json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
        }

        if(isset($params['params']['target_sex']) && $params['params']['target_sex'] !="0"){
            //$query->where('target_id', '=',$params['params']['target']);
            $target_condition = '{"ID":'.$params['params']['target_sex'].'}';
            $target_field = '$.Targets';
            $query->whereRaw('JSON_CONTAINS (list_article.json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
        }

        // if(isset($params['params']['area']) && $params['params']['area'] !="0"){
        //     $query->where('area_id', '=',$params['params']['area']);
        // }

        if(isset($params['params']['keyword'])){
            $keyword = $params['params']['keyword'];
            $keyword = explode(" ", $keyword);
            //dd($keyword);
            //dd(count($keyword));
            $sql_like_title = '';
            $sql_like_description = '';
            $sql_like_keyword = '';
            $sql_like_tags = '';

            //$query->leftjoin('view_data_tags_media','list_article.id', '=', 'view_data_tags_media.data_id');

            // $query->leftJoin('view_data_tags_media', function($join){
            //     $join->on('list_article.article_id', '=', 'view_data_tags_media.data_id')
            //          ->on('list_article.article_type', '=', 'view_data_tags_media.data_type');
            // });

            foreach ($keyword as $key=>$value) {
                $value = str_replace('"', '\\"', $value);
                $value = str_replace("'", "\\'", $value);
                //dd($value);
                if($key ==0){
                  $sql_like_title .='list_article.title like "%'.$value.'%"';
                  $sql_like_description .='list_article.description like "'.$value.'%"';    
                  $sql_like_keyword .="JSON_SEARCH(JSON_EXTRACT(list_article.json_data, '$.Keywords'), 'all', '".$value."') !=''";
                  //$sql_like_tags .='view_data_tags_media.title like "%'.$value.'%"';   
                  $sql_like_tags .="JSON_SEARCH (list_article.tags,'all', '".$value."') !=''";                
               }else{
                  $sql_like_title .=' OR list_article.title like "%'.$value.'%"';
                  $sql_like_description .=' OR list_article.description like "'.$value.'%"';
                  $sql_like_keyword .=" OR JSON_SEARCH(JSON_EXTRACT(list_article.json_data, '$.Keywords'), 'all', '".$value."') !=''";
                 //$sql_like_tags .=' OR view_data_tags_media.title like "%'.$value.'%"'; 
                  $sql_like_tags .=" OR JSON_SEARCH (list_article.tags,'all', '".$value."') !=''";   
               }
 
            }
                //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
                $query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.') OR ('.$sql_like_tags.'))');
        }


        if(isset($params['params']['filter'])){
            if($params['params']['filter'] =='media_campaign'){
                $query->where('list_article.media_campaign','=','2');
            }else if($params['params']['filter'] =='knowledges'){
                $query->where('list_article.knowledges','=','2');
            }
        }

        //$keyword = $params['params']['keyword'];

        // $query->leftjoin('view_data_tags_media', function ($join) use ($keyword) {
        //         //dd($language);
        //     $join->on('list_article.id', '=','view_data_tags_media.data_id')
        //         ->whereRaw('(view_data_tags_media.title like "%'.$keyword.'%")');
        // });

        //$query->orderByRaw('featured DESC,created_at DESC');
        $query->where('list_article.status','=','publish');

        // เพิ่มส่วนที่ให้เลือกเวลา
        // if(isset($params['article_in_date'])){
        //     $query->whereIn('list_article.article_id', $params['article_in_date']);
        // }

        $query->orderByRaw('list_article.created_at DESC');
        
        // dd($query);

        // dd($query->toSql());
        // $data = $query->simplePaginate(9);
        $data = $query->Paginate(9);
        //$result['items']= $data;
        // dd($data);
        return $data; 

    }

    // public function scopeFrontListJsonSearch($query,$params){

    //     //$result = array();
    //     //dd($params);
    //     //$query->select('id');
    //     $query=DB::table('view_search_media')->select(
    //         'id',
    //         'title',
    //         'description',
    //         'hit',
    //         'json_data',
    //         'created_at',
    //         'template',
    //         'article_type',
    //         'slug',
    //         'tags',
    //         'category_id',
    //         'keyword'
    //     );
        
    //     //$query->where('featured','=',"1");
    
           
    //     if(isset($params['params']['issue']) && $params['params']['issue'] !="0"){
    //         //$query->where('issues_id', '=',$params['params']['issue']);
    //         $issues_condition = '{"ID":'.$params['params']['issue'].'}';
    //         $issues_field = '$.Issues';
    //         $query->whereRaw('JSON_CONTAINS (json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')');
    //     }

    //     if(isset($params['params']['template']) && $params['params']['template'] !="0"){
    //         $query->where('template', '=',$params['params']['template']);
    //     }

    //     if(isset($params['params']['target']) && $params['params']['target'] !="0"){
    //         //$query->where('target_id', '=',$params['params']['target']);
    //         $target_condition = '{"ID":'.$params['params']['target'].'}';
    //         $target_field = '$.Targets';
    //         $query->whereRaw('JSON_CONTAINS (json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
    //     }

    //     if(isset($params['params']['target_age']) && $params['params']['target_age'] !="0"){
    //         //$query->where('target_id', '=',$params['params']['target']);
    //         $target_condition = '{"ID":'.$params['params']['target_age'].'}';
    //         $target_field = '$.Targets';
    //         $query->whereRaw('JSON_CONTAINS (json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
    //     }

    //     if(isset($params['params']['target_sex']) && $params['params']['target_sex'] !="0"){
    //         //$query->where('target_id', '=',$params['params']['target']);
    //         $target_condition = '{"ID":'.$params['params']['target_sex'].'}';
    //         $target_field = '$.Targets';
    //         $query->whereRaw('JSON_CONTAINS (json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
    //     }

    //     // if(isset($params['params']['area']) && $params['params']['area'] !="0"){
    //     //     $query->where('area_id', '=',$params['params']['area']);
    //     // }

    //     if(isset($params['params']['keyword'])){
    //         $keyword = $params['params']['keyword'];
    //         $keyword = explode(" ", $keyword);


    //         //dd($keyword);
    //         //dd(count($keyword));
    //         $sql_like_title = '';
    //         $sql_like_description = '';
    //         $sql_like_keyword = '';
    //         $sql_like_tags = '';

    //         //$query->leftjoin('view_data_tags_media','id', '=', 'view_data_tags_media.data_id');

    //         // $query->leftJoin('view_data_tags_media', function($join){
    //         //     $join->on('article_id', '=', 'view_data_tags_media.data_id')
    //         //          ->on('article_type', '=', 'view_data_tags_media.data_type');
    //         // });

       
    //         $query->where(function($query_where) use ($keyword)  {
    //             foreach ($keyword as $key=>$value)  {
    //                 $value = str_replace('"', '\\"', $value) ; // คำค้นlaravel 
    //                 $value = str_replace("'", "\\'", $value);
    //                 if($key ==0){
    //                     $query_where->where('title',"Like","%".$value."%");
            
    //                 }else{
    //                     $query_where->orwhere('title',"Like","%".$value."%");
            
    //                 }
    //             }

    //         });


            
    //        $query->orwhere(function($query_where) use ($keyword)  {
    //            foreach ($keyword as $key=>$value) {
    //             $value = str_replace('"', '\\"', $value) ; // คำค้นlaravel 
    //             $value = str_replace("'", "\\'", $value);
    //                if($key ==0){
    //                    $query_where->where('description',"Like","%".$value."%");

           
    //                }else{
    //                    $query_where->orwhere('description',"Like","%".$value."%");

           
    //                }
    //            }
       
    //        });
        
    //          $query->orwhere(function($query_where) use ($keyword)  {
    //              foreach ($keyword as $key=>$value) {
    //                 $value = str_replace('"', '\\"', $value) ; // คำค้นlaravel 
    //                 $value = str_replace("'", "\\'", $value);
    //                  if($key ==0){
    //                     $query_where->whereRaw("JSON_SEARCH (tags,'all', '".$value."') !=''");
            
    //                  }else{
    //                      $query_where->orwhereRaw("JSON_SEARCH (tags,'all', '".$value."') !=''");
             
    //                  }
              
    //                 }
    //             });
     
                


    //     //     $query->orwhere(function($query_where) use ($keyword)  {
    //     //    foreach ($keyword as $key=>$value) {
    //     //        if($key ==0){
    //     //         $query_where->where('keyword',"Like","%".$value."%");
    //     //         }else{
    //     //         $query_where->orwhere('keyword',"Like","%".$value."%");
    //     //        }
    //     //    }

    //     //});
    //     //
       

    //         //foreach ($keyword as $key=>$value) {
    //         //    //dd($value);
    //         //    if($key ==0){
    //         //      $sql_like_title .='title like "%'.$value.'%"';
    //         //      $sql_like_description .='description like "'.$value.'%"';    
    //           //    $sql_like_keyword .="JSON_SEARCH(JSON_EXTRACT(json_data, '$.Keywords'), 'all', '".$value."') !=''";
    //         //      //$sql_like_tags .='view_data_tags_media.title like "%'.$value.'%"';   
    //         //      $sql_like_tags .="JSON_SEARCH (tags,'all', '".$value."') !=''";                
    //         //   }else{
    //         //      $sql_like_title .=' OR title like "%'.$value.'%"';
    //         //      $sql_like_description .=' OR description like "'.$value.'%"';
    //         //      $sql_like_keyword .=" OR JSON_SEARCH(JSON_EXTRACT(json_data, '$.Keywords'), 'all', '".$value."') !=''";
    //         //     //$sql_like_tags .=' OR view_data_tags_media.title like "%'.$value.'%"'; 
    //         //      $sql_like_tags .=" OR JSON_SEARCH (tags,'all', '".$value."') !=''";   
    //         //   }
 
    //         //}
    //         //   //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
    //            // $query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.') OR ('.$sql_like_tags.'))');
    //     }


    //     if(isset($params['params']['filter'])){
    //         if($params['params']['filter'] =='media_campaign'){
    //             $query->where('media_campaign','=','2');
    //         }else if($params['params']['filter'] =='knowledges'){
    //             $query->where('knowledges','=','2');
    //         }
    //     }

    
    //     //$keyword = $params['params']['keyword'];

    //     // $query->leftjoin('view_data_tags_media', function ($join) use ($keyword) {
    //     //         //dd($language);
    //     //     $join->on('id', '=','view_data_tags_media.data_id')
    //     //         ->whereRaw('(view_data_tags_media.title like "%'.$keyword.'%")');
    //     // });

    //     //$query->orderByRaw('featured DESC,created_at DESC');

 

    //     // เพิ่มส่วนที่ให้เลือกเวลา
    //     // if(isset($params['article_in_date'])){
    //     //     $query->whereIn('article_id', $params['article_in_date']);
    //     // }


    //     $query->orderByRaw('created_at DESC');
        
        
   

    //     // dd($query->toSql());
    //     // $data = $query->simplePaginate(9);
        
    //     $data = $query->Paginate(9);
    //     //$result['items']= $data;
    //     return $data; 


    // }

    
    public function scopeFrontListRelated($query,$params){

        //$result = array();
        //dd($params);
        //$query->select('id');
        $query->select('list_article.article_id AS id','list_article.title','list_article.description','list_article.hit','list_article.json_data','list_article.created_at','list_article.updated_at','list_article.template','list_article.article_type AS data_type','list_article.slug','list_article.tags');
        $sql_like_tags = '';

        foreach ($params['tags_select'] as $key=>$value) {
                //dd($value);
            if($key ==0){ 
                $sql_like_tags .="JSON_SEARCH (list_article.tags,'all', '".$value."') !=''";               
            }else{
                $sql_like_tags .=" OR JSON_SEARCH (list_article.tags,'all', '".$value."') !=''";   
            }
 
        }
        $query->whereRaw('('.$sql_like_tags.')');
      
        $query->where('list_article.status','=','publish');
        $query->where('list_article.article_id','!=',$params['id']);
        $query->orderByRaw('list_article.created_at DESC');
        $query->limit(10);
        //dd($query->toSql());
        //$data = $query->simplePaginate(9);
        $data = $query->get();
        //$result['items']= $data;
        //dd($data);
        return $data; 

    }
    

    public function scopeFrontListJsonSearchWebView($query,$params){

        //$result = array();
        //dd($params);
        //$query->select('id');
        $query->select('list_article.article_id AS id','list_article.title','list_article.description','list_article.hit','list_article.json_data','list_article.created_at','list_article.template','list_article.article_type','list_article.slug');
        
        //$query->where('featured','=',"1");

        if(isset($params['params']['issue']) && $params['params']['issue'] !="0"){
            //$query->where('issues_id', '=',$params['params']['issue']);
            $issues_condition = '{"ID":'.str_replace("'","",$params['params']['issue']).'}';
            $issues_field = '$.Issues';
            $query->whereRaw('JSON_CONTAINS (list_article.json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')');
        }

        if(isset($params['params']['sex']) && $params['params']['sex'] !="0"){

            //$query->where('list_article.sex', '=',$params['params']['sex']);
            $query->whereRaw('JSON_CONTAINS (list_article.sex,'."'".str_replace("'","",$params['params']['sex'])."'".')');
        }

        if(isset($params['params']['age']) && $params['params']['age'] !="0"){

            //dd($params['params']['age']);
            $age = $params['params']['age'];
            $age = explode(",", $age);
            //dd($age);
            $sql_age = '';
            foreach ($age as $key => $value) {
                if($key ==0){
                  $sql_age .='JSON_CONTAINS (list_article.age,'."'".$value."'".')';             
               }else{
                  $sql_age .=' OR JSON_CONTAINS (list_article.age,'."'".$value."'".')';
               }
            }
            $query->whereRaw('(('.$sql_age.'))');
            //$query->whereRaw('JSON_CONTAINS (list_article.age,'."'".str_replace("'","",$params['params']['age'])."'".')');

        }

        if(isset($params['params']['target']) && $params['params']['target'] !="0"){
            //$query->where('target_id', '=',$params['params']['target']);
            $target_condition = '{"ID":'.str_replace("'","",$params['params']['target']).'}';
            $target_field = '$.Targets';
            $query->whereRaw('JSON_CONTAINS (list_article.json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
        }


        if(isset($params['params']['setting']) && $params['params']['setting'] !="0"){
            //$query->where('target_id', '=',$params['params']['target']);
            $setting_condition = '{"ID":'.str_replace("'","",$params['params']['setting']).'}';
            $setting_field = '$.Settings';
            $query->whereRaw('JSON_CONTAINS (list_article.json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')');
        }        

        // if(isset($params['params']['area']) && $params['params']['area'] !="0"){
        //     $query->where('area_id', '=',$params['params']['area']);
        // }

        if(isset($params['params']['keyword'])){
            $keyword = $params['params']['keyword'];
            $keyword = explode(" ", $keyword);
            //dd($keyword);
            //dd(count($keyword));
            $sql_like_title = '';
            $sql_like_description = '';
            $sql_like_keyword = '';
            $sql_like_tags = '';

            //$query->leftjoin('view_data_tags_media','list_article.id', '=', 'view_data_tags_media.data_id');

            // $query->leftJoin('view_data_tags_media', function($join){
            //     $join->on('list_article.article_id', '=', 'view_data_tags_media.data_id')
            //          ->on('list_article.article_type', '=', 'view_data_tags_media.data_type');
            // });

            foreach ($keyword as $key=>$value) {
                //dd($value);
                if($key ==0){
                  $sql_like_title .='list_article.title like "%'.$value.'%"';
                  $sql_like_description .='list_article.description like "'.$value.'%"';    
                  $sql_like_keyword .="JSON_SEARCH(JSON_EXTRACT(list_article.json_data, '$.Keywords'), 'all', '".$value."') !=''";
                  //$sql_like_tags .='view_data_tags_media.title like "%'.$value.'%"';   
                  $sql_like_tags .="JSON_SEARCH (list_article.tags,'all', '".$value."') !=''";                
               }else{
                  $sql_like_title .=' OR list_article.title like "%'.$value.'%"';
                  $sql_like_description .=' OR list_article.description like "'.$value.'%"';
                  $sql_like_keyword .=" OR JSON_SEARCH(JSON_EXTRACT(list_article.json_data, '$.Keywords'), 'all', '".$value."') !=''";
                 //$sql_like_tags .=' OR view_data_tags_media.title like "%'.$value.'%"'; 
                  $sql_like_tags .=" OR JSON_SEARCH (list_article.tags,'all', '".$value."') !=''";   
               }
 
            }
                //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
                //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_keyword.'))');
                $query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.') OR ('.$sql_like_tags.'))');
        }


        if(isset($params['params']['filter'])){
            if($params['params']['filter'] =='media_campaign'){
                $query->where('list_article.media_campaign','=','2');
            }else if($params['params']['filter'] =='knowledges'){
                $query->where('list_article.knowledges','=','2');
            }
        }
        //$keyword = $params['params']['keyword'];

        // $query->leftjoin('view_data_tags_media', function ($join) use ($keyword) {
        //         //dd($language);
        //     $join->on('list_article.id', '=','view_data_tags_media.data_id')
        //         ->whereRaw('(view_data_tags_media.title like "%'.$keyword.'%")');
        // });

        //$query->orderByRaw('featured DESC,created_at DESC');
        $query->where('list_article.status','=','publish');
        $query->where('list_article.web_view','=','1');
        // if(isset($params['params']['keyword'])){
        //     $query->groupBy('list_article.id');
        // }
        $query->orderByRaw('list_article.featured DESC');
        $query->orderByRaw('list_article.created_at DESC');
        //dd($query->toSql());
        //$data = $query->simplePaginate(9);
        $data = $query->Paginate(9);
        //$result['items']= $data;
        //dd($data);
        return $data; 

    }    


    public function scopeData2($query,$params)
    {

        $limit = (isset($params['request']['limit']) ? $params['request']['limit']:10);
        //dd($params,$limit);
        //$query->select('id','title','hit AS visitors','issues_id','updated_at','download');

        $query->select('id','title','recommend','articles_research','include_statistics','notable_books','interesting_issues','featured','created_at','created_by','updated_by','status','issues_id');
            
        

        // if(isset($params['request']['issue']) && $params['request']['issue'] !=0){
        //     $query->where('issues_id', '=',$params['request']['issue']);
        // }

        // if(isset($params['request']['template']) && $params['request']['template'] !="0"){
        //     $query->where('template', '=',$params['request']['template']);
        // }

        // if(isset($params['request']['target']) && $params['request']['target'] !="0"){
        //     $query->where('target_id', '=',$params['request']['target']);
        // }


        if(isset($params['params']['issue']) && $params['params']['issue'] !="0"){
            //$query->where('issues_id', '=',$params['params']['issue']);
            $issues_condition = '{"ID":'.$params['params']['issue'].'}';
            $issues_field = '$.Issues';
            $query->whereRaw('JSON_CONTAINS (json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')');
        }

        if(isset($params['params']['template']) && $params['params']['template'] !="0"){
            $query->where('template', '=',$params['params']['template']);
        }

        if(isset($params['params']['target']) && $params['params']['target'] !="0"){
            //$query->where('target_id', '=',$params['params']['target']);
            $target_condition = '{"ID":'.$params['params']['target'].'}';
            $target_field = '$.Targets';
            $query->whereRaw('JSON_CONTAINS (json_data,'."'".$target_condition."'".','."'".$target_field."'".')');
        }

        if(isset($params['request']['status']) && $params['request']['status'] !="0"){
            $query->where('status', '=',$params['request']['status']);
        }else{
            $query->whereIn('status',['publish','draft']);
        }

        if(isset($params['request']['users']) && $params['request']['users'] !="0"){
            $query->where('updated_by', '=',$params['request']['users']);
        }

        if((isset($params['request']['start_date']) && $params['request']['start_date'] !='') &&  (isset($params['request']['end_date']) && $params['request']['end_date'] !='')){
            //dd(str_replace("/","-",$params['request']['start_date']));

            $start_date = date("Y-m-d",strtotime(str_replace("/","-",$params['request']['start_date'])));
            $end_date = date("Y-m-d",strtotime(str_replace("/","-",$params['request']['end_date'])));

            //dd($start_date,$end_date);
            $query->where('created_at','>=',$start_date);
            $query->where('created_at','<=',$end_date);     
        }
 
        if(isset($params['request']['keyword'])){
            $query->whereRaw('title like "%'.$params['request']['keyword'].'%"');
        }

        //$query->orderBy('updated_at', 'desc');
        
        if(isset($params['request']['ordering'])){
            //dd("Params Ordering",$params['request']['ordering'],explode(':',$params['request']['ordering']));
            $ex = explode(':',$params['request']['ordering']);
            $query->orderBy($ex[0],$ex[1]);
        }else{
            $query->orderBy('created_at','desc');
        }
        
        //$query->groupBy('id');
        $query->with('issueName')->with('updatedBy');
        //dd($query->toSql());

        return $query->Paginate($limit);

    }
   


    public function scopeFrontListNotableBooks($query,$params){

        //$result = array();
        //dd($params);
        $query->select('id','title','json_data');
        $query->where('status','=','publish');
        $query->where('notable_books','=',"2");
        $query->where('title','!=','');
        $query->orderByRaw('featured,created_at DESC');
        $data = $query->get();
        //$result['items']= $data;
        return $data;

    }

    public function scopeListMediaCaseTop10Related($query,$params){

        $query->selectRaw('id,title,description,recommend,hit,json_data,created_at,updated_at,CONCAT("media") AS data_type');
        $query->whereIn('id',(count($params['id']) ? $params['id']:['hap']));
        $query->orderByRaw('created_at DESC');
        $data = $query->get();
        //$result['items']= $data;
        return $data;

    }


    public function scopeListMediaCaseTop10MostView($query,$params){

        $query->select('id','title','description','recommend','hit','json_data','created_at','updated_at');
        $query->whereIn('id',(count($params['id']) ? $params['id']:['hap']));
        $query->orderByRaw('hit DESC');
        $data = $query->get();
        //$result['items']= $data;
        return $data;

    }

    public function scopeListMediaCaseTop10($query,$params){
        //dd(count($params['id']));
        $query->select('id','title','description','recommend','hit','json_data','created_at','updated_at');
        $query->whereIn('id',(count($params['id']) ? $params['id']:['hap']));
        $query->orderByRaw('created_at DESC');
        $data = $query->get();
        //dd($data);
        //$result['items']= $data;
        return $data;

    }

    

    public function scopeDetail($query,$params){
        $lang = $this->getLang();
        $query->select('id','title','description','interesting_issues','hit','json_data','template','featured','created_at','created_by','updated_by','status');
            $query->where('id','=',$params['id']);
        return $query->first();
    }


    public function scopeFrontDataId($query,$params){

        $query->select('id','title','json_data');
            $query->where('id','=',$params['id']);
        return $query->first();
    }


    public function scopeDetailKnowledgesMediaCampaign($query,$params){
        $lang = $this->getLang();
        $query->select('id','title','description','json_data');
            $query->where('id','=',$params['id']);
        return $query->first();
    }

    public function scopeDetailKnowledges($query,$params){
        $lang = $this->getLang();
        $query->select('id','title','description','json_data');
        $query->where('knowledges','=','2');
        $query->orderBy('id','desc');
        return $query->first();
    }

    public function scopeDetailMediaCampaign($query,$params){
        $lang = $this->getLang();
        $query->select('id','title','description','json_data');
        $query->where('media_campaign','=','2');
        $query->orderBy('id','desc');
        return $query->first();
    }

    public function setUrlknowledgesAttribute($id){
        $lang = \App::getLocale();
        $segment = \Request::segment(2);
        return $this->attributes['urlknowledges'] =  route('knowledges-detail',\Hashids::encode($id));
    }
    public function setUrlmediacampaignAttribute($id){
        $lang = \App::getLocale();
        $segment = \Request::segment(2);
        return $this->attributes['urlmediacampaign'] =  route('media-campaign-detail',\Hashids::encode($id));
    }

    

    public function createdBy()
    {
        return $this->belongsTo('App\User','created_by','id')->select('name','id');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User','updated_by','id')->select('name','id');
    }


    public function registerMediaConversions()
    {
        $this
            ->addMediaConversion('thumb1366x635')
            ->width(1366)
            ->height(635)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb1024x618')
            ->width(1024)
            ->height(618)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb560x338')
            ->width(560)
            ->height(338)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb270x168')
            ->width(270)
            ->height(168)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb200x200')
            ->width(200)
            ->height(200)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb226x127')
            ->width(226)
            ->height(127)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb133x80')
            ->width(133)
            ->height(80)
            ->performOnCollections('cover_desktop');

        /*End desktop */

        $this
            ->addMediaConversion('thumb1366x768px')
            ->width(1366)
            ->height(768)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb1024x618')
            ->width(1024)
            ->height(618)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb560x338')
            ->width(560)
            ->height(338)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb270x168')
            ->width(270)
            ->height(168)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb200x200')
            ->width(200)
            ->height(200)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb226x127')
            ->width(226)
            ->height(127)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb133x80')
            ->width(133)
            ->height(80)
            ->performOnCollections('gallery_desktop');

        /*End gallery_desktop*/
    }

   
}
