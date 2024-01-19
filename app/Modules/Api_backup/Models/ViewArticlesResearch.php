<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class ViewArticlesResearch extends Model
{
    protected $table = 'view_articles_research';
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
        'id',
        'recommend',
        'slug',
        'page_layout',
        'data_type'
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


    public function scopeFrontMain($query,$params){

        //$result = array();
        //dd($params);
        //$query->select('id');
        $query->select('title','description','json_data','slug','data_type');
        $query->where('status','=','publish');
        //$query->where('featured','=',"1");
        $query->orderByRaw('featured,created_at DESC');
        //dd($query->toSql());
        $data = $query->limit(2)->get();
        //$result['items']= $data;
        //dd($data->count());
        return $data;

    }

    public function scopeFrontList($query,$params){

        //$result = array();
        //dd($params);
        //$query->select('id');
        $query->select('id','title','description','hit','json_data','slug','page_layout','data_type','created_at','updated_at');
        $query->where('status','=','publish');
        //$query->where('featured','=',"1");
        $query->orderByRaw('featured DESC,updated_at DESC');
        //dd($query->toSql());
        $data = $query->paginate(7);
        //$result['items']= $data;
        return $data;

    }


    public function scopeFrontRss($query,$params){

        $query->select('id','title','description','json_data','slug','page_layout','data_type','updated_at');
        $query->where('status','=','publish');
        $query->orderByRaw('updated_at DESC');
        $query->limit($params['limit']);
        $data = $query->get();
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

   
}
