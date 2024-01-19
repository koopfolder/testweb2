<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class ListMediaTest extends Model implements HasMediaConversions
{
    use HasMediaTrait;
    protected $table = 'list_media_test';
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
        'department_id'

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

        $query->select('id','title','recommend','articles_research','include_statistics','notable_books','interesting_issues','featured','created_at','updated_at','created_by','updated_by','status');
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

        $query->select('id','title','description','recommend','hit','json_data','created_at','updated_at');
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
