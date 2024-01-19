<?php

namespace App\Modules\Whatfranchise\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;


class Whatfranchise extends Model implements HasMediaConversions
{
   use Sluggable, HasMediaTrait;
    protected $table = 'tbl_news';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'featured',
        'status',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'hit',
        'order',
        'start_date',
        'end_date',
        'shot_description',
        'page_layout',
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

        $query->select('id','title','description','created_at','created_by','updated_by','status');
        return $query->where('page_layout','=',$params['page_layout'])->with('createdBy','updatedBy')
            ->orderBy('created_at','DESC')
            //->toSql();
            ->first();
    }

    public function scopeFrontData($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();

        $query->select('id','title','shot_description','created_at','created_by','updated_by','status','featured','created_at');
        if($params['featured'] ===true){
                $query->where('featured','=',2);
        }else{
                $query->where('featured','!=',2);
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

   public function scopeFrontHighlight($query,$params){

        $lang = $this->getLang();
        $check = $this->checkHighlight();
        $result = array();
        //dd($params);
        if($check >0){

            if($params['page'] ==1){
                
                $query->select('id','title_th','title_en','created_at','status','featured','date_event');
                $query->where('status','=','publish');
                $query->where('page_layout','=',$params['page_layout']);
                //$query->where('featured','=',"1");
                $query->orderByRaw('featured DESC ,date_event DESC');
                $data = $query->paginate(7);
                $result['highlight']= true;
                $result['items']= $data;

            }else{

                $query->select('id','title_th','title_en','created_at','status','featured','date_event');
                $query->where('status','=','publish');
                $query->where('page_layout','=',$params['page_layout']);
                //$query->where('featured','=',"1");
                $query->orderByRaw('featured DESC ,date_event DESC');
                $data = $query->paginate(7);
                $result['highlight']= true;
                $result['items']= $data;

            }

        }else{

            $query->select('id','title_th','title_en','created_at','status','featured','date_event');
            $query->where('status','=','publish');
            $query->where('page_layout','=',$params['page_layout']);
                //$query->where('featured','=',"1");
            $query->orderByRaw('date_event DESC');
            $data = $query->paginate(7);
            $result['highlight']= false;
            $result['items']= $data;

        }
        return $result;

    }

   public function scopeFrontList($query,$params){

        //$result = array();
        //dd($params);
        $query->select('id','title','description','slug','hit','created_at','status','featured');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
        //$query->where('featured','=',"1");
        $query->orderByRaw('featured,created_at DESC');
        $data = $query->paginate(7);
        //$result['items']= $data;
        return $data;

    }

   public function scopeFrontListRelated($query,$params){

        //$result = array();
        //dd($params);
        $query->select('id','title','shot_description','slug','hit','created_at','status','featured');
        $query->where('status','=','publish');
        $query->where('page_layout','=',$params['page_layout']);
        $query->where('id','!=',$params['related_id']);
        $query->orderByRaw('featured,created_at DESC');
        $data = $query->get();
        //$result['items']= $data;
        return $data;
        
    }


    public function checkHighlight(){
        $data = Article::select('id')->where('featured','=','2')->count();
        return $data;
    }

    public function setUrlnewsAttribute($id){
        $lang = \App::getLocale();
        $segment = \Request::segment(2);
        //article-detail
        return $this->attributes['urlnews'] =  route('news-detail',\Hashids::encode($id));
    }

    public function setUrlpromotionAttribute($id){
        $lang = \App::getLocale();
        $segment = \Request::segment(2);
        //article-detail
        return $this->attributes['urlpromotion'] =  route('promotion-detail',\Hashids::encode($id));
    }

    public function scopeDetail($query,$params){
        $lang = $this->getLang();
            $query->select('id','title','description','hit','created_at','status','meta_title','meta_keywords','meta_description');
            $query->where('id','=',$params['id']);

        return $query->first();
    }

    public function scopeBreadcrumb($query,$params){
        $lang = $this->getLang();
        $query->select('id','title_th','title_en');
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
