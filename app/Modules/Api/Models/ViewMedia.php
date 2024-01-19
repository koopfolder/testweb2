<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class ViewMedia extends Model
{
    protected $table = 'view_media';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'description',
        'interesting_issues',
        'description',
        'featured',
        'category_id',
        'province',
        'template',
        'area_id',
        'UploadFileID',
        'json_data',
        'status',
        'issues_id', 
        'keyword',
        'target_id',
        'id',
        'recommend',
        'articles_research',
        'include_statistics',
        'notable_books',
        'slug',
        'page_layout',
        'data_type',
        'download',
        'knowledges',
        'media_campaign'
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
        $query->select('id','title','description','interesting_issues','featured','json_data','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params['status'])
            ->orderBy('created_at','DESC')
            //->toSql();
            ->get();
    }


    public function scopeData2($query,$params)
    {

        $limit = (isset($params['request']['limit']) ? $params['request']['limit']:10);
        //dd($params,$limit);
        //$query->select('id','title','hit AS visitors','issues_id','updated_at','download');

        $query->select('id','title','recommend','articles_research','include_statistics','notable_books','interesting_issues','featured','json_data','created_at','created_by','updated_by','status','issues_id','knowledges','media_campaign');
            
        if(isset($params['request']['issue']) && $params['request']['issue'] !=0){
            $query->where('issues_id', '=',$params['request']['issue']);
        }

        if(isset($params['request']['template']) && $params['request']['template'] !="0"){
            $query->where('template', '=',$params['request']['template']);
        }

        if(isset($params['request']['target']) && $params['request']['target'] !="0"){
            $query->where('target_id', '=',$params['request']['target']);
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
        $sql_like_title = '';
        $sql_like_description = '';
        $sql_like_keyword = '';
        if(isset($params['request']['keyword'])){
            //$query->whereRaw('title like "%'.$params['request']['keyword'].'%"');
            $sql_like_title .='title like "%'.$params['request']['keyword'].'%"';
            $sql_like_description .='description like "'.$params['request']['keyword'].'%"';    
            $sql_like_keyword .="JSON_SEARCH(JSON_EXTRACT(json_data, '$.Keywords'), 'all', '".$params['request']['keyword']."') !=''";     
            $query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
        }

        //$query->orderBy('updated_at', 'desc');
        $query->where('title','!=','ข้อมูลไม่ผ่านการคัดกรอง');
        
        if(isset($params['request']['ordering'])){
            //dd("Params Ordering",$params['request']['ordering'],explode(':',$params['request']['ordering']));
            $ex = explode(':',$params['request']['ordering']);
            $query->orderBy($ex[0],$ex[1]);
        }else{
            $query->orderBy('created_at','desc');
        }
        
        $query->groupBy('id');
        $query->with('issueName')->with('updatedBy');
        //dd($query->toSql());

        return $query->Paginate($limit);

    }
   



    public function scopeDataDropDown($query,$params)
    {
        $query->select('id','title');
        return $query->whereIn('status',$params['status'])
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

    public function scopeFrontList($query,$params){

        //$result = array();
        //dd($params);
        //$query->select('id');
        $query->select('id','title','description','hit','json_data','slug','page_layout','data_type','created_at','updated_at','template');
        $query->where('status','=','publish');

        //$query->where('featured','=',"1");

        if(isset($params['params']['issue']) && $params['params']['issue'] !="0"){
            $query->where('issues_id', '=',$params['params']['issue']);
        }

        if(isset($params['params']['template']) && $params['params']['template'] !="0"){
            $query->where('template', '=',$params['params']['template']);
        }

        if(isset($params['params']['target']) && $params['params']['target'] !="0"){
            $query->where('target_id', '=',$params['params']['target']);
        }

        if(isset($params['params']['area']) && $params['params']['area'] !="0"){
            $query->where('area_id', '=',$params['params']['area']);
        }

        if(isset($params['params']['keyword'])){
            $keyword = $params['params']['keyword'];
            $keyword = explode(" ", $keyword);
            //dd($keyword);
            //dd(count($keyword));
            $sql_like_title = '';
            $sql_like_description = '';
            $sql_like_keyword = '';
            foreach ($keyword as $key=>$value) {
                //dd($value);
                if($key ==0){
                  $sql_like_title .='title like "%'.$value.'%"';
                  //$sql_like_description .='description like "'.$value.'%"';    
                  //$sql_like_keyword .='keyword like "%'.$value.'%"';                  
               }else{
                  $sql_like_title .=' OR title like "%'.$value.'%"';
                  //$sql_like_description .=' OR description like "'.$value.'%"';
                  //$sql_like_keyword .=' OR keyword like "%'.$value.'%"';
               }
            }
                //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
                $query->whereRaw('('.$sql_like_title.')');
        }

        //$query->orderByRaw('featured DESC,created_at DESC');
        $query->orderByRaw('id DESC');
        $query->groupBy('updated_at');
        //dd($query->toSql());
        //$data = $query->simplePaginate(9);
        $data = $query->Paginate(9);
        //$result['items']= $data;
        //dd($data);
        return $data;

    }

    public function scopeDetail($query,$params){
        $lang = $this->getLang();
        $query->select('id','title','description','interesting_issues','json_data','template','featured','created_at','created_by','updated_by','status','created_at');
            $query->where('id','=',$params['id']);
        return $query->first();
    }


    public function scopeFrontListRelated($query,$params){

        //dd(gettype($params['issues']));
        //dd($issue_array);
        $query->select('id');
        $query->where('status','=','publish');
        $query->whereIn('issues_id',$params['issues']);
        $query->where('id','!=',$params['related_id']);
        $query->orderByRaw('featured,created_at DESC');
        $query->groupBy('id');
        $query->limit(10);
        $data = $query->get();
        //$result['items']= $data;
        //dd($data);
        return $data;
        
    }


    public function scopeFrontListMostView($query,$params){

        //$result = array();
        //dd($params);
        $query->select('id');
        $query->where('status','=','publish');
        $query->whereIn('issues_id',$params['issues']);   
        $query->orderByRaw('hit DESC');
        $query->groupBy('id');
        $query->limit(10);
        $data = $query->get();
        //$result['items']= $data;
        return $data;
        
    }

        
    public function scopeFrontListRecommend($query,$params){

        //$result = array();
        //dd($params);
        $query->select('id');
        $query->where('status','=','publish');
        $query->whereIn('issues_id',$params['issues']);   
        $query->where('recommend','=','2');
        $query->where('id','!=',$params['related_id']);     
        $query->orderByRaw('created_at DESC');
        $query->groupBy('id');
        $query->limit(10);
        $data = $query->get();
        //$result['items']= $data;
        return $data;
        
    }


    public function scopeReport($query,$params)
    {
        
        $limit = (isset($params['request']['limit']) ? $params['request']['limit']:10);
        //dd($params,$limit);
        $query->select('id','title','hit AS visitors','issues_id','updated_at','download');
            
        $query->where('status','=','publish');

        if(isset($params['request']['issue']) && $params['request']['issue'] !=0){
            $query->where('issues_id', '=',$params['request']['issue']);
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
        $query->groupBy('id');
        $query->with('issueName');

        return $query->paginate($limit);

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


    public function issueName()
    {
        return $this->belongsTo('App\Modules\Api\Models\ListIssue','issues_id','issues_id')->select('name','issues_id');
    }

   
}
