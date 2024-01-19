<?php

namespace App\Modules\Article\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class ThaiHealthWatchSendEmailLogs extends Model implements HasMediaConversions
{
    use HasMediaTrait;
    protected $table = 'thai_health_watch_send_email_logs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'subject',
        'description',
        'to',
        'status',
        'created_at',
        'updated_at', 
        'created_by',
        'updated_by'
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
        //dd($params);
        $lang = $this->getLang();
        // if($lang === 'th'){
        //     $query->select('year','description_th AS description','created_at','created_by','updated_by');
        // }else{
        //     $query->select('year','description_en AS description','created_at','created_by','updated_by');
        // }
        $query->select('id','subject','description','to','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params['status'])->with('createdBy','updatedBy')
            ->orderBy('created_at','DESC')
            //->toSql();
            ->get();
    }


    public function scopeDataHealth($query,$params)
    {
        //dd($params);
        $lang = $this->getLang();
        // if($lang === 'th'){
        //     $query->select('year','description_th AS description','created_at','created_by','updated_by');
        // }else{
        //     $query->select('year','description_en AS description','created_at','created_by','updated_by');
        // }
            $query->select('id','title','slug','description','created_at','created_by','updated_by','status','category_id','api','dol_UploadFileID');
        return $query->whereIn('status',$params['status'])->where('page_layout','=',$params['page_layout'])->with('createdBy','updatedBy')
            ->orderBy('created_at','DESC')
            //->toSql();
            ->get();
    }


    public function scopeFrontData($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();
        $query->select('id','title','short_description AS description','hit','slug','created_at','created_by','updated_by','status','featured','updated_at');
        if($params['featured'] ===true){
                $query->where('featured','=',2);
        }else{
                //$query->where('featured','!=',2);
        }
        if($params['retrieving_results'] =='first'){
            return $query->whereIn('status',$params['status'])
                     ->where('page_layout','=',$params['page_layout'])
                     ->with('createdBy','updatedBy')
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     ->first();
        }else{
            return $query->whereIn('status',$params['status'])
                     ->where('page_layout','=',$params['page_layout'])
                     ->with('createdBy','updatedBy')
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        }

    }


    public function scopeFrontDataNews($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();

        $query->select('id','title','short_description AS description','hit','slug','created_at','updated_at');
        if($params['featured'] ===true){
                $query->where('featured','=',2);
        }else{
                //$query->where('featured','!=',2);
        }
        if($params['retrieving_results'] =='first'){
            return $query->whereIn('status',$params['status'])
                     ->where('page_layout','=',$params['page_layout'])
                     ->limit($params['limit'])
                     ->orderByRaw('featured DESC,created_at DESC')
                     ->first();
        }else{
            return $query->whereIn('status',$params['status'])
                     ->where('page_layout','=',$params['page_layout'])
                     ->limit($params['limit'])
                     ->orderByRaw('featured DESC,created_at DESC')
                     ->get();

        }

    }


    public function scopeFrontDataRssFeed($query,$params)
    {
        //dd($params);
        $query->select('id','title','short_description AS description','slug','updated_at');
        return $query->whereIn('status',$params['status'])
                     ->where('page_layout','=',$params['page_layout'])
                     ->limit($params['limit'])
                     ->orderByRaw('updated_at DESC')
                     ->get();

    }


    public function scopeFrontDataInterestingIssuesNews($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();

        $query->select('id','title','short_description AS description','slug','dol_cover_image');
        if($params['featured'] ===true){
                $query->where('featured','=',2);
        }else{
                //$query->where('featured','!=',2);
        }
        if($params['retrieving_results'] =='first'){
            return $query->whereIn('status',$params['status'])
                     ->where('page_layout','=',$params['page_layout'])
                     ->limit($params['limit'])
                     ->orderByRaw('featured DESC,created_at DESC')
                     ->first();
        }else{
            return $query->whereIn('status',$params['status'])
                     ->where('page_layout','=',$params['page_layout'])
                     ->limit($params['limit'])
                     ->orderByRaw('featured DESC,created_at DESC')
                     ->get();

        }

    }


    public function scopeFrontHealthLiteracy($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();

        $query->select('id');
        if($params['featured'] ===true){
                $query->where('featured','=',2);
        }else{
                //$query->where('featured','!=',2);
        }
        if($params['retrieving_results'] =='first'){
            return $query->whereIn('status',$params['status'])
                     ->where('page_layout','=',$params['page_layout'])
                     ->limit($params['limit'])
                     ->orderByRaw('featured DESC,created_at DESC')
                     ->first();
        }else{
            return $query->whereIn('status',$params['status'])
                     ->where('page_layout','=',$params['page_layout'])
                     ->limit($params['limit'])
                     ->orderByRaw('featured DESC,created_at DESC')
                     ->get();

        }

    }

    public function scopeFrontDataArticlesResearch($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();

        $query->select('id','title','short_description AS description','slug','dol_cover_image');
        if($params['featured'] ===true){
                $query->where('featured','=',2);
        }else{
                //$query->where('featured','!=',2);
        }
        if($params['retrieving_results'] =='first'){
            return $query->whereIn('status',$params['status'])
                     ->where('page_layout','=',$params['page_layout'])
                     ->limit($params['limit'])
                     //->orderBy('created_at', 'desc')
                     ->orderByRaw('featured DESC,created_at DESC')
                     ->first();
        }else{
            return $query->whereIn('status',$params['status'])
                     ->where('page_layout','=',$params['page_layout'])
                     ->limit($params['limit'])
                     //->orderBy('created_at', 'desc')
                     ->orderByRaw('featured DESC,created_at DESC')
                     ->get();

        }

    }


    public function scopeFrontDataIncludeStatistics($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();

        $query->select('id','title','short_description AS description','slug','dol_cover_image');
        if($params['featured'] ===true){
                $query->where('featured','=',2);
        }else{
                //$query->where('featured','!=',2);
        }
        if($params['retrieving_results'] =='first'){
            return $query->whereIn('status',$params['status'])
                     ->where('page_layout','=',$params['page_layout'])
                     ->limit($params['limit'])
                     ->orderByRaw('featured DESC,created_at DESC')
                     ->first();
        }else{
            return $query->whereIn('status',$params['status'])
                     ->where('page_layout','=',$params['page_layout'])
                     ->limit($params['limit'])
                     ->orderByRaw('featured DESC,created_at DESC')
                     ->get();

        }

    }

    
    public function scopeFrontList($query,$params){

        //$result = array();
        //dd($params);
        $date = date('Y-m-d H:i:s');
        $query->select('id','title','short_description AS description','slug','hit','created_at','status','featured','updated_at','dol_cover_image');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
        $query->where('start_date','<=',$date);
        $query->where('end_date','>=',$date);
        //$query->where('featured','=',"1");
        $query->orderByRaw('featured DESC,created_at DESC');
        //dd($query->toSql());
        $data = $query->paginate(7);
        //$result['items']= $data;

        //dd($data);
        return $data;

    }

    public function scopeFrontListNcds6($query,$params){

        //$result = array();
        //dd($params);
        $date = date('Y-m-d H:i:s');
        $query->select('id','title','short_description AS description','slug','hit','created_at','status','featured','updated_at','dol_cover_image');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
        $query->where('start_date','<=',$date);
        $query->where('end_date','>=',$date);


        // if(isset($params['params']['age']) && $params['params']['age'] !="0"){

        //     //dd($params['params']['age']);
        //     $age = $params['params']['age'];
        //     $age = explode(",", $age);
        //     //dd($age);
        //     $sql_age = '';
        //     foreach ($age as $key => $value) {
        //         if($key ==0){
        //           $sql_age .='JSON_CONTAINS (age,'."'".$value."'".')';             
        //        }else{
        //           $sql_age .=' OR JSON_CONTAINS (age,'."'".$value."'".')';
        //        }
        //     }
        //     $query->whereRaw('(('.$sql_age.'))');
        //     //$query->whereRaw('JSON_CONTAINS (list_article.age,'."'".str_replace("'","",$params['params']['age'])."'".')');

        // }        

        // if(isset($params['params']['keyword'])){
        //     $keyword = $params['params']['keyword'];
        //     $keyword = explode(" ", $keyword);
        //     //dd($keyword);
        //     //dd(count($keyword));
        //     $sql_like_title = '';
        //     $sql_like_description = '';
        //     $sql_like_keyword = '';
        //     foreach ($keyword as $key=>$value) {
        //         //dd($value);
        //         if($key ==0){
        //           $sql_like_title .='title like "%'.$value.'%"';
        //           $sql_like_description .='description like "'.$value.'%"';    
        //           //$sql_like_keyword .='keyword like "%'.$value.'%"';                  
        //        }else{
        //           $sql_like_title .=' OR title like "%'.$value.'%"';
        //           $sql_like_description .=' OR description like "'.$value.'%"';
        //           //$sql_like_keyword .=' OR keyword like "%'.$value.'%"';
        //        }
        //     }
        //         //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
        //         $query->whereRaw('('.$sql_like_title.') OR ('.$sql_like_description.')');
        // }


        if(isset($params['params']['issue']) && $params['params']['issue'] !="0"){
            //$query->where('issues_id', '=',$params['params']['issue']);
            $issue_ex =  explode(",", $params['params']['issue']);
            //dd($issue_ex);
            $sql_issue ='(';
            foreach ($issue_ex as $key => $value) {
                //dd($key,$value);
                $issues_condition = '{"ID":'.$value.'}';
                $issues_field = '$.Issues';
                if($key ==0){
                    $sql_issue .= ' JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
                }else{
                    $sql_issue .=' OR JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
                }
            }
            $sql_issue .= ')';
            $query->whereRaw($sql_issue);
        }

        if(isset($params['params']['template']) && $params['params']['template'] !="0"){
            $query->where('dol_template', '=',$params['params']['template']);
        }

        if(isset($params['params']['target']) && $params['params']['target'] !="0"){
            //$query->where('target_id', '=',$params['params']['target']);
            $target_ex =  explode(",", $params['params']['target']);
            $sql_target ='(';
            foreach ($target_ex as $key => $value) {
                //dd($value);

                $target_condition = '{"ID":'.$value.'}';
                $target_field = '$.Targets';

                if($key ==0){
                    $sql_target .= ' JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                }else{
                    $sql_target .=' OR JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                }

            }
            $sql_target .= ')';
            $query->whereRaw($sql_target);

        }


        if(isset($params['params']['setting']) && $params['params']['setting'] !="0"){
            //$query->where('issues_id', '=',$params['params']['issue']);
            $setting_ex =  explode(",", $params['params']['setting']);
            $sql_setting ='(';
            foreach ($setting_ex as $key => $value) {
                //dd($value);
                $setting_condition = '{"ID":'.$value.'}';
                $setting_field = '$.Settings';

                if($key ==0){
                    $sql_setting .= ' JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                }else{
                    $sql_setting .=' OR JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                }
            }
            $sql_setting .= ')';
            $query->whereRaw($sql_setting);

        }



        if(isset($params['params']['keyword'])){
            $keyword = $params['params']['keyword'];
            $keyword = explode(" ", $keyword);
            //dd($keyword);
            //dd(count($keyword));
            $sql_like_title = '';
            $sql_like_description = '';
            $sql_like_keyword = '';
            $sql_like_tags = '';
            foreach ($keyword as $key=>$value) {
                //dd($value);
                if($key ==0){
                  $sql_like_title .='title like "%'.$value.'%"';
                  $sql_like_description .='description like "'.$value.'%"';
                  $sql_like_keyword .="JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";    
                  $sql_like_tags .="JSON_SEARCH (tags,'all', '".$value."') !=''";                      
               }else{
                  $sql_like_title .=' OR title like "%'.$value.'%"';
                  $sql_like_description .=' OR description like "'.$value.'%"';
                  $sql_like_keyword .=" OR JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";
                  $sql_like_tags .=" OR JSON_SEARCH (tags,'all', '".$value."') !=''"; 
               }
            }
                $query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.') OR ('.$sql_like_tags.'))');
                //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
                //$query->whereRaw('('.$sql_like_title.') OR ('.$sql_like_description.')');
        }




        //$query->where('featured','=',"1");
        $query->orderByRaw('featured DESC,created_at DESC');
        //dd($query->toSql());
        $data = $query->paginate(7);
        //$result['items']= $data;

        //dd($data);
        return $data;

    }


    public function scopeFrontListNcds4($query,$params){

        //$result = array();
        //dd($params);
        $date = date('Y-m-d H:i:s');
        $query->select('id','title','short_description AS description','slug','hit','created_at','status','featured','updated_at','dol_cover_image','dol_json_data');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
        $query->where('start_date','<=',$date);
        $query->where('end_date','>=',$date);


        // if(isset($params['params']['age']) && $params['params']['age'] !="0"){

        //     //dd($params['params']['age']);
        //     $age = $params['params']['age'];
        //     $age = explode(",", $age);
        //     //dd($age);
        //     $sql_age = '';
        //     foreach ($age as $key => $value) {
        //         if($key ==0){
        //           $sql_age .='JSON_CONTAINS (age,'."'".$value."'".')';             
        //        }else{
        //           $sql_age .=' OR JSON_CONTAINS (age,'."'".$value."'".')';
        //        }
        //     }
        //     $query->whereRaw('(('.$sql_age.'))');
        //     //$query->whereRaw('JSON_CONTAINS (list_article.age,'."'".str_replace("'","",$params['params']['age'])."'".')');

        // }        

        // if(isset($params['params']['keyword'])){
        //     $keyword = $params['params']['keyword'];
        //     $keyword = explode(" ", $keyword);
        //     //dd($keyword);
        //     //dd(count($keyword));
        //     $sql_like_title = '';
        //     $sql_like_description = '';
        //     $sql_like_keyword = '';
        //     foreach ($keyword as $key=>$value) {
        //         //dd($value);
        //         if($key ==0){
        //           $sql_like_title .='title like "%'.$value.'%"';
        //           $sql_like_description .='description like "'.$value.'%"';    
        //           //$sql_like_keyword .='keyword like "%'.$value.'%"';                  
        //        }else{
        //           $sql_like_title .=' OR title like "%'.$value.'%"';
        //           $sql_like_description .=' OR description like "'.$value.'%"';
        //           //$sql_like_keyword .=' OR keyword like "%'.$value.'%"';
        //        }
        //     }
        //         //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
        //         $query->whereRaw('('.$sql_like_title.') OR ('.$sql_like_description.')');
        // }

        if(isset($params['params']['issue']) && $params['params']['issue'] !="0"){
            //$query->where('issues_id', '=',$params['params']['issue']);
            $issue_ex =  explode(",", $params['params']['issue']);
            //dd($issue_ex);
            $sql_issue ='(';
            foreach ($issue_ex as $key => $value) {
                //dd($key,$value);
                $issues_condition = '{"ID":'.$value.'}';
                $issues_field = '$.Issues';
                if($key ==0){
                    $sql_issue .= ' JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
                }else{
                    $sql_issue .=' OR JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
                }
            }
            $sql_issue .= ')';
            $query->whereRaw($sql_issue);
        }

        if(isset($params['params']['template']) && $params['params']['template'] !="0"){
            $query->where('dol_template', '=',$params['params']['template']);
        }

        if(isset($params['params']['target']) && $params['params']['target'] !="0"){
            //$query->where('target_id', '=',$params['params']['target']);
            $target_ex =  explode(",", $params['params']['target']);
            $sql_target ='(';
            foreach ($target_ex as $key => $value) {
                //dd($value);

                $target_condition = '{"ID":'.$value.'}';
                $target_field = '$.Targets';

                if($key ==0){
                    $sql_target .= ' JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                }else{
                    $sql_target .=' OR JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                }

            }
            $sql_target .= ')';
            $query->whereRaw($sql_target);

        }


        if(isset($params['params']['setting']) && $params['params']['setting'] !="0"){
            //$query->where('issues_id', '=',$params['params']['issue']);
            $setting_ex =  explode(",", $params['params']['setting']);
            $sql_setting ='(';
            foreach ($setting_ex as $key => $value) {
                //dd($value);
                $setting_condition = '{"ID":'.$value.'}';
                $setting_field = '$.Settings';

                if($key ==0){
                    $sql_setting .= ' JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                }else{
                    $sql_setting .=' OR JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                }
            }
            $sql_setting .= ')';
            $query->whereRaw($sql_setting);

        }



        if(isset($params['params']['keyword'])){
            $keyword = $params['params']['keyword'];
            $keyword = explode(" ", $keyword);
            //dd($keyword);
            //dd(count($keyword));
            $sql_like_title = '';
            $sql_like_description = '';
            $sql_like_keyword = '';
            $sql_like_tags = '';
            foreach ($keyword as $key=>$value) {
                //dd($value);
                if($key ==0){
                  $sql_like_title .='title like "%'.$value.'%"';
                  $sql_like_description .='description like "'.$value.'%"';
                  $sql_like_keyword .="JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";    
                  $sql_like_tags .="JSON_SEARCH (tags,'all', '".$value."') !=''";                     
               }else{
                  $sql_like_title .=' OR title like "%'.$value.'%"';
                  $sql_like_description .=' OR description like "'.$value.'%"';
                  $sql_like_keyword .=" OR JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";
                  $sql_like_tags .=" OR JSON_SEARCH (tags,'all', '".$value."') !=''";     
               }
            }
                $query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.') OR ('.$sql_like_tags.'))');
                //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
                //$query->whereRaw('('.$sql_like_title.') OR ('.$sql_like_description.')');
        }



        //$query->where('featured','=',"1");
        $query->orderByRaw('featured DESC,created_at DESC');
        //dd($query->toSql());
        $data = $query->paginate(7);
        //$result['items']= $data;

        //dd($data);
        return $data;

    }

    public function scopeFrontListNcds1($query,$params){

        //$result = array();
        //dd($params);
        $date = date('Y-m-d H:i:s');
        $query->select('id','title','short_description AS description','slug','hit','created_at','status','featured','updated_at','dol_cover_image','dol_json_data');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
        $query->where('start_date','<=',$date);
        $query->where('end_date','>=',$date);

        // if(isset($input['category']) && $input['category'] !="0"){
        //     //dd($input['age']);
        //     $category = $input['category'];
        //     $query->where('category_id','=',$category);
        // }

        if(isset($params['params']['issue']) && $params['params']['issue'] !="0"){
            //$query->where('issues_id', '=',$params['params']['issue']);
            $issue_ex =  explode(",", $params['params']['issue']);
            //dd($issue_ex);
            $sql_issue ='(';
            foreach ($issue_ex as $key => $value) {
                //dd($key,$value);
                $issues_condition = '{"ID":'.$value.'}';
                $issues_field = '$.Issues';
                if($key ==0){
                    $sql_issue .= ' JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
                }else{
                    $sql_issue .=' OR JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
                }
            }
            $sql_issue .= ')';
            $query->whereRaw($sql_issue);
        }

        if(isset($params['params']['template']) && $params['params']['template'] !="0"){
            $query->where('dol_template', '=',$params['params']['template']);
        }

        if(isset($params['params']['target']) && $params['params']['target'] !="0"){
            //$query->where('target_id', '=',$params['params']['target']);
            $target_ex =  explode(",", $params['params']['target']);
            $sql_target ='(';
            foreach ($target_ex as $key => $value) {
                //dd($value);

                $target_condition = '{"ID":'.$value.'}';
                $target_field = '$.Targets';

                if($key ==0){
                    $sql_target .= ' JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                }else{
                    $sql_target .=' OR JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                }

            }
            $sql_target .= ')';
            $query->whereRaw($sql_target);

        }


        if(isset($params['params']['setting']) && $params['params']['setting'] !="0"){
            //$query->where('issues_id', '=',$params['params']['issue']);
            $setting_ex =  explode(",", $params['params']['setting']);
            $sql_setting ='(';
            foreach ($setting_ex as $key => $value) {
                //dd($value);
                $setting_condition = '{"ID":'.$value.'}';
                $setting_field = '$.Settings';

                if($key ==0){
                    $sql_setting .= ' JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                }else{
                    $sql_setting .=' OR JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                }
            }
            $sql_setting .= ')';
            $query->whereRaw($sql_setting);

        }


        if(isset($params['params']['keyword'])){
            $keyword = $params['params']['keyword'];
            $keyword = explode(" ", $keyword);
            //dd($keyword);
            //dd(count($keyword));
            $sql_like_title = '';
            $sql_like_description = '';
            $sql_like_keyword = '';
            $sql_like_tags = '';
            foreach ($keyword as $key=>$value) {
                //dd($value);
                if($key ==0){
                  $sql_like_title .='title like "%'.$value.'%"';
                  $sql_like_description .='description like "'.$value.'%"';
                  $sql_like_keyword .="JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";    
                  $sql_like_tags .="JSON_SEARCH (tags,'all', '".$value."') !=''";                      
               }else{
                  $sql_like_title .=' OR title like "%'.$value.'%"';
                  $sql_like_description .=' OR description like "'.$value.'%"';
                  $sql_like_keyword .=" OR JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";
                  $sql_like_tags .=" OR JSON_SEARCH (tags,'all', '".$value."') !=''";    
               }
            }
                $query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.') OR ('.$sql_like_tags.'))');
                //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
                //$query->whereRaw('('.$sql_like_title.') OR ('.$sql_like_description.')');
        }


        //$query->where('featured','=',"1");
        $query->orderByRaw('featured DESC,created_at DESC');
        //dd($query->toSql());
        $data = $query->paginate(7);
        //$result['items']= $data;

        //dd($data);
        return $data;

    }

    public function scopeFrontListHealthLiteracy($query,$params){

        //$result = array();
       // dd($params['params']);
        $date = date('Y-m-d H:i:s');
        $query->select('id','title','short_description AS description','slug','hit','created_at','status','featured','updated_at','dol_cover_image');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
       //$query->where('category_id','=',$params['category_id']);
        $query->whereRaw('JSON_CONTAINS (category_id,'."'".str_replace("'","",$params['category_id'])."'".')');
        $query->where('start_date','<=',$date);
        $query->where('end_date','>=',$date);




        // if(isset($params['params']['issue']) && $params['params']['issue'] !="0"){
        //     //$query->where('issues_id', '=',$params['params']['issue']);
        //     $issue_ex =  explode(",", $params['params']['issue']);
        //     //dd($issue_ex);
        //     $sql_issue ='(';
        //     foreach ($issue_ex as $key => $value) {
        //         //dd($key,$value);
        //         $issues_condition = '{"ID":'.$value.'}';
        //         $issues_field = '$.Issues';
        //         if($key ==0){
        //             $sql_issue .= ' JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
        //         }else{
        //             $sql_issue .=' OR JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
        //         }
        //     }
        //     $sql_issue .= ')';
        //     $query->whereRaw($sql_issue);
        // }

        if(isset($params['params']['template']) && $params['params']['template'] !="0"){
            $query->where('dol_template', '=',$params['params']['template']);
        }

        if(isset($params['params']['target']) && $params['params']['target'] !="0"){
            //$query->where('target_id', '=',$params['params']['target']);
            $target_ex =  explode(",", $params['params']['target']);
            $sql_target ='(';
            foreach ($target_ex as $key => $value) {
                //dd($value);

                $target_condition = '{"ID":'.$value.'}';
                $target_field = '$.Targets';

                if($key ==0){
                    $sql_target .= ' JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                }else{
                    $sql_target .=' OR JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                }

            }
            $sql_target .= ')';
            $query->whereRaw($sql_target);

        }


        if(isset($params['params']['setting']) && $params['params']['setting'] !="0"){
            //$query->where('issues_id', '=',$params['params']['issue']);
            $setting_ex =  explode(",", $params['params']['setting']);
            $sql_setting ='(';
            foreach ($setting_ex as $key => $value) {
                //dd($value);
                $setting_condition = '{"ID":'.$value.'}';
                $setting_field = '$.Settings';

                if($key ==0){
                    $sql_setting .= ' JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                }else{
                    $sql_setting .=' OR JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                }
            }
            $sql_setting .= ')';
            $query->whereRaw($sql_setting);

        }



        if(isset($params['params']['keyword'])){
            $keyword = $params['params']['keyword'];
            $keyword = explode(" ", $keyword);
            //dd($keyword);
            //dd(count($keyword));
            $sql_like_title = '';
            $sql_like_description = '';
            $sql_like_keyword = '';
            $sql_like_tags = '';
            foreach ($keyword as $key=>$value) {
                //dd($value);
                if($key ==0){
                  $sql_like_title .='title like "%'.$value.'%"';
                  $sql_like_description .='description like "'.$value.'%"';
                  $sql_like_keyword .="JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";    
                  $sql_like_tags .="JSON_SEARCH (tags,'all', '".$value."') !=''";                     
               }else{
                  $sql_like_title .=' OR title like "%'.$value.'%"';
                  $sql_like_description .=' OR description like "'.$value.'%"';
                  $sql_like_keyword .=" OR JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";
                  $sql_like_tags .=" OR JSON_SEARCH (tags,'all', '".$value."') !=''";   
               }
            }
                $query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.') OR ('.$sql_like_tags.'))');
                //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
                //$query->whereRaw('('.$sql_like_title.') OR ('.$sql_like_description.')');
        }

        $query->whereRaw("json_extract(article.dol_json_data, '$.Keywords') LIKE '%healthliteracy%'");

        //$query->where('featured','=',"1");
        $query->orderByRaw('featured DESC,created_at DESC');
        //dd($query->toSql());
        $data = $query->paginate(7);
        //$result['items']= $data;

        //dd($data);
        return $data;

    }

    public function scopeFrontListHealthLiteracy2($query,$params){

        //$result = array();
       // dd($params['params']);
        $date = date('Y-m-d H:i:s');
        $query->select('id','title','short_description AS description','slug','hit','created_at','status','featured','updated_at','dol_cover_image');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
        if($params['category_id'] !=''){
            //$query->where('category_id','=',$params['category_id']);
            $query->whereRaw('JSON_CONTAINS (category_id,'."'".str_replace("'","",$params['category_id'])."'".')');
        }

        $query->where('start_date','<=',$date);
        $query->where('end_date','>=',$date);




        // if(isset($params['params']['issue']) && $params['params']['issue'] !="0"){
        //     //$query->where('issues_id', '=',$params['params']['issue']);
        //     $issue_ex =  explode(",", $params['params']['issue']);
        //     //dd($issue_ex);
        //     $sql_issue ='(';
        //     foreach ($issue_ex as $key => $value) {
        //         //dd($key,$value);
        //         $issues_condition = '{"ID":'.$value.'}';
        //         $issues_field = '$.Issues';
        //         if($key ==0){
        //             $sql_issue .= ' JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
        //         }else{
        //             $sql_issue .=' OR JSON_CONTAINS (dol_json_data,'."'".$issues_condition."'".','."'".$issues_field."'".')';
        //         }
        //     }
        //     $sql_issue .= ')';
        //     $query->whereRaw($sql_issue);
        // }

        if(isset($params['params']['template']) && $params['params']['template'] !="0"){
            $query->where('dol_template', '=',$params['params']['template']);
        }

        if(isset($params['params']['target']) && $params['params']['target'] !="0"){
            //$query->where('target_id', '=',$params['params']['target']);
            $target_ex =  explode(",", $params['params']['target']);
            $sql_target ='(';
            foreach ($target_ex as $key => $value) {
                //dd($value);

                $target_condition = '{"ID":'.$value.'}';
                $target_field = '$.Targets';

                if($key ==0){
                    $sql_target .= ' JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                }else{
                    $sql_target .=' OR JSON_CONTAINS (dol_json_data,'."'".$target_condition."'".','."'".$target_field."'".')';
                }

            }
            $sql_target .= ')';
            $query->whereRaw($sql_target);

        }


        if(isset($params['params']['setting']) && $params['params']['setting'] !="0"){
            //$query->where('issues_id', '=',$params['params']['issue']);
            $setting_ex =  explode(",", $params['params']['setting']);
            $sql_setting ='(';
            foreach ($setting_ex as $key => $value) {
                //dd($value);
                $setting_condition = '{"ID":'.$value.'}';
                $setting_field = '$.Settings';

                if($key ==0){
                    $sql_setting .= ' JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                }else{
                    $sql_setting .=' OR JSON_CONTAINS (dol_json_data,'."'".$setting_condition."'".','."'".$setting_field."'".')';
                }
            }
            $sql_setting .= ')';
            $query->whereRaw($sql_setting);

        }



        if(isset($params['params']['keyword'])){
            $keyword = $params['params']['keyword'];
            $keyword = explode(" ", $keyword);
            //dd($keyword);
            //dd(count($keyword));
            $sql_like_title = '';
            $sql_like_description = '';
            $sql_like_keyword = '';
            $sql_like_tags = '';
            foreach ($keyword as $key=>$value) {
                //dd($value);
                if($key ==0){
                  $sql_like_title .='title like "%'.$value.'%"';
                  $sql_like_description .='description like "'.$value.'%"';
                  $sql_like_keyword .="JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";    
                  $sql_like_tags .="JSON_SEARCH (tags,'all', '".$value."') !=''";                   
               }else{
                  $sql_like_title .=' OR title like "%'.$value.'%"';
                  $sql_like_description .=' OR description like "'.$value.'%"';
                  $sql_like_keyword .=" OR JSON_SEARCH(JSON_EXTRACT(dol_json_data, '$.Keywords'), 'all', '".$value."') !=''";
                  $sql_like_tags .=" OR JSON_SEARCH (tags,'all', '".$value."') !=''";   
               }
            }
                $query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.') OR ('.$sql_like_tags.'))');
                //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
                //$query->whereRaw('('.$sql_like_title.') OR ('.$sql_like_description.')');
        }


        //$query->where('featured','=',"1");
        $query->orderByRaw('featured DESC,created_at DESC');
        //dd($query->toSql());
        $data = $query->paginate(7);
        //$result['items']= $data;

        //dd($data);
        return $data;

    }
    
   
    public function scopeFrontEventCalendarList($query,$params){

        //$result = array();
        //dd($params);
        $query->select('id','title','slug','start_date');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
        $query->where('start_date','>=',$params['start_date']);
        $query->where('start_date','<=',$params['end_date']);
        //$query->where('featured','=',"1");
        $query->groupBy(\DB::raw("DATE_FORMAT(start_date,'%Y-%m-%d')")); 
        $query->orderByRaw('featured DESC,start_date ASC');
        //dd($query->toSql());
        $data = $query->get();
        //$result['items']= $data;

        //dd($data);
        return $data;

    }


    public function scopeFrontEventCalendarFull($query,$params){

        //$result = array();
        //dd($params);
        $query->select('title','slug','start_date','end_date');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
        $query->orderByRaw('featured DESC,start_date ASC');
        //dd($query->toSql());
        $data = $query->get();
        //$result['items']= $data;

        //dd($data);
        return $data;

    }



    public function scopeFrontListRelated($query,$params){

        //$result = array();
        //dd($params);
        $date = date('Y-m-d H:i:s');
        $query->selectRaw('id,title,short_description AS description,slug,hit,created_at,status,CONCAT("article") AS data_type');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
        $query->where('id','!=',$params['related_id']);
        $query->where('start_date','<=',$date);
        $query->where('end_date','>=',$date);       
        $query->orderByRaw('featured,created_at DESC');
        $query->limit(10);
        $data = $query->get();
        //$result['items']= $data;
        return $data;
        
    }


    public function scopeFrontListRelatedHealthLiteracy($query,$params){

        //$result = array();
        //dd($params);
        $date = date('Y-m-d H:i:s');
        $query->select('id','title','short_description AS description','slug','hit','created_at','status');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
        //$query->where('category_id','=',$params['category_id']);
        $query->whereRaw('JSON_CONTAINS (category_id,'."'".str_replace("'","",$params['category_id'])."'".')');
        $query->where('id','!=',$params['related_id']);
        $query->where('start_date','<=',$date);
        $query->where('end_date','>=',$date);       
        $query->orderByRaw('featured,created_at DESC');
        $query->limit(10);
        $data = $query->get();
        //$result['items']= $data;
        return $data;
        
    }


    public function scopeFrontListRelated2($query,$params){

        //$result = array();
        //dd($params);
        $date = date('Y-m-d H:i:s');
        $query->selectRaw('id,title,short_description AS description,slug,hit,created_at,status,CONCAT("article") AS data_type');
        $query->where('status','=','publish');
        $query->whereIn('id',$params['related_id']);
        $query->where('start_date','<=',$date);
        $query->where('end_date','>=',$date);       
        $query->orderByRaw('featured,created_at DESC');
        //$query->limit(10);
        $data = $query->get();
        //$result['items']= $data;
        return $data;
        
    }


    
    public function scopeFrontListRecommend($query,$params){

        //$result = array();
        //dd($params);
        $date = date('Y-m-d H:i:s');
        $query->select('id','title','short_description AS description','slug','hit','created_at','status');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
        $query->where('recommend','=','2');
        $query->where('id','!=',$params['related_id']);
        $query->where('start_date','<=',$date);
        $query->where('end_date','>=',$date);       
        $query->orderByRaw('created_at DESC');
        $query->limit(10);
        $data = $query->get();
        //$result['items']= $data;
        return $data;
        
    }


    public function scopeFrontListRecommendHealthLiteracy($query,$params){

        //$result = array();
        //dd($params);
        $date = date('Y-m-d H:i:s');
        $query->select('id','title','short_description AS description','slug','hit','created_at','status');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
        //$query->where('category_id','=',$params['category_id']);
        $query->whereRaw('JSON_CONTAINS (category_id,'."'".str_replace("'","",$params['category_id'])."'".')');
        $query->where('recommend','=','2');
        $query->where('id','!=',$params['related_id']);
        $query->where('start_date','<=',$date);
        $query->where('end_date','>=',$date);       
        $query->orderByRaw('created_at DESC');
        $query->limit(10);
        $data = $query->get();
        //$result['items']= $data;
        return $data;
        
    }


    public function scopeFrontListMostView($query,$params){

        //$result = array();
        //dd($params);
        $date = date('Y-m-d H:i:s');
        $query->select('id','title','short_description AS description','slug','hit','created_at','status');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
        $query->where('start_date','<=',$date);
        $query->where('end_date','>=',$date);       
        $query->orderByRaw('hit DESC');
        $query->limit(10);
        $data = $query->get();
        //$result['items']= $data;
        return $data;
        
    }


    public function scopeFrontListMostViewHealthLiteracy($query,$params){

        //$result = array();
        //dd($params);
        $date = date('Y-m-d H:i:s');
        $query->select('id','title','short_description AS description','slug','hit','created_at','status');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
        //$query->where('category_id','=',$params['category_id']);
        $query->whereRaw('JSON_CONTAINS (category_id,'."'".str_replace("'","",$params['category_id'])."'".')');
        $query->where('start_date','<=',$date);
        $query->where('end_date','>=',$date);       
        $query->orderByRaw('hit DESC');
        $query->limit(10);
        $data = $query->get();
        //$result['items']= $data;
        return $data;
        
    }


    public function scopeReport($query,$params)
    {
        
        $limit = (isset($params['request']['limit']) ? $params['request']['limit']:10);
        //dd($params,$limit);
        $query->select('id','title','hit AS visitors','page_layout','updated_at');
            
        $query->where('status','=','publish');

        if(isset($params['request']['data_type']) && $params['request']['data_type'] !=0){
            $query->where('page_layout', '=',$params['request']['data_type']);
        }

        if((isset($params['request']['start_date']) && $params['request']['start_date'] !='') &&  (isset($params['request']['end_date']) && $params['request']['end_date'] !='')){
            //dd(str_replace("/","-",$params['request']['start_date']));

            $start_date = date("Y-m-d",strtotime(str_replace("/","-",$params['request']['start_date'])));
            $end_date = date("Y-m-d",strtotime(str_replace("/","-",$params['request']['end_date'])));

            //dd($start_date,$end_date);
            $query->where('updated_at','>=',$start_date);
            $query->where('updated_at','<=',$end_date);     
        }
 

        if(isset($params['request']['keyword'])){
            $query->whereRaw('title like "%'.$params['request']['keyword'].'%"');
        }

        $query->orderBy('updated_at', 'desc');
        $query->with('document_reports');

        return $query->paginate($limit);

    }


    public function setUrlnewsAttribute($id){
        $lang = \App::getLocale();
        $segment = \Request::segment(2);
        return $this->attributes['urlnews'] =  route('news-detail',\Hashids::encode($id));
    }


    public function setCountdownloadAttribute($data){
        $count = 0;
        foreach ($data as $value) {
            $count = $count + $value->download;
        }
        return $this->attributes['countdownload'] = $count;
    }


    public function setPlayoutAttribute($data){
        //dd($data);
        switch ($data) {

            case 'sook_library':
                $result = trans('article::backend.sook-library');
                break;

            case 'interesting_issues':
                $result = trans('article::backend.interesting-issues');
                break;     

            case 'e-learning':
                $result = trans('article::backend.e-learning');
                break;    

            case 'training_course':
                $result = trans('article::backend.training-course');
                break;    

            case 'news_event':
                $result = trans('article::backend.news_events');
                break;    

            case 'include_statistics':
                $result = trans('article::backend.include-statistics');
                break;    

            case 'articles_research':
                $result = trans('article::backend.articles-research');
                break;    

            case 'our_service':
                $result = trans('article::backend.our-service');
                break;

            default:
                $result = '';
                break;
        }
        return $this->attributes['playout'] = $result;
    }


    public function scopeDetail($query,$params){
        $lang = $this->getLang();
        $query->select('id','title','description','hit','created_at','updated_at','status','meta_title','meta_keywords','meta_description','page_layout','dol_url','dol_json_data','dol_UploadFileID','category_id','slug','tags');
        $query->where('slug','=',$params['slug']);
            
        return $query->first();
    }

    public function scopeDetailId($query,$params){
        $lang = $this->getLang();
            $query->select('id','title','description','hit','created_at','updated_at','status','meta_title','meta_keywords','meta_description','dol_url','category_id');
            $query->where('id','=',$params['id']);

        return $query->first();
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User','created_by','id')->select('name','id');
    }


    public function category()
    {
        return $this->belongsTo('App\Modules\Article\Models\ArticleCategory','category_id','id')->select('title','id');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User','updated_by','id')->select('name','id');
    }

    public function frontDocuments()
    {
        $lang = \App::getLocale();
        if($lang =='th'){
        return  $this->hasMany('App\Modules\Documentsdownload\Models\Documents','article_id','id')->select('id','title_th AS title','file_path_th AS file_path','article_id');
        }else{
        return  $this->hasMany('App\Modules\Documentsdownload\Models\Documents','article_id','id')->select('id','title_en AS title','file_path_en AS file_path','article_id');
        }
    }


    public function documents()
    {
        return $this->hasMany('App\Modules\Documentsdownload\Models\Documents','article_id','id')->select('id','title_th','title_en','file_path_th','file_path_en','article_id');
    }


    public function document_reports()
    {
        return $this->hasMany('App\Modules\Documentsdownload\Models\Documents','model_id','id')->select('model_id','download','updated_at');
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
