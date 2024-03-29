<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class ListMedia extends Model implements HasMediaConversions
{
    use HasMediaTrait;
    protected $table = 'list_media';
    protected $primaryKey = 'id';

    protected $fillable = [
        'UploadFileID',
        'json_data',
        'title',
        'description',
        'interesting_issues',
        'featured',
        'category_id',
        'province',
        'template',
        'area_id',
        'status',
        'created_at',
        'updated_at', 
        'created_by',
        'updated_by',
        'recommend',
        'articles_research',
        'include_statistics',
        'notable_books',
        'download',
        'department_id',
        'health_literacy',
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

        $query->select('id','title','description','json_data');

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
        $query->select('id','title','description','hit','json_data','created_at','template');
        

        //$query->where('featured','=',"1");

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
            foreach ($keyword as $key=>$value) {
                //dd($value);
                if($key ==0){
                  $sql_like_title .='title like "%'.$value.'%"';
                  $sql_like_description .='description like "'.$value.'%"';    
                  $sql_like_keyword .="JSON_SEARCH(JSON_EXTRACT(json_data, '$.Keywords'), 'all', '".$value."') !=''";                  
               }else{
                  $sql_like_title .=' OR title like "%'.$value.'%"';
                  $sql_like_description .=' OR description like "'.$value.'%"';
                  $sql_like_keyword .=" OR JSON_SEARCH(JSON_EXTRACT(json_data, '$.Keywords'), 'all', '".$value."') !=''";
               }
            }
                //$query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
                $query->whereRaw('(('.$sql_like_title.') OR ('.$sql_like_description.') OR ('.$sql_like_keyword.'))');
        }


        if(isset($params['params']['filter'])){
            if($params['params']['filter'] =='media_campaign'){
                $query->where('media_campaign','=','2');
            }else if($params['params']['filter'] =='knowledges'){
                $query->where('knowledges','=','2');
            }
        }

        //$query->orderByRaw('featured DESC,created_at DESC');
        $query->where('status','=','publish');
        $query->orderByRaw('created_at DESC');
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
