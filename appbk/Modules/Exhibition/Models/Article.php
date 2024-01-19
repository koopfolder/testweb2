<?php

namespace App\Modules\Exhibition\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Article extends Model implements HasMediaConversions
{
    use Sluggable, HasMediaTrait;
    protected $table = 'article';
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
        'short_description',
        'page_layout',
        'created_at',
        'updated_at', 
        'created_by',
        'updated_by',
        'recommend'
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
            $query->select('id','title','description','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params['status'])->where('page_layout','=',$params['page_layout'])->with('createdBy','updatedBy')
            ->orderBy('created_at','DESC')
            //->toSql();
            ->get();
    }

    public function scopeFrontData($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();

        $query->select('id','title','short_description','slug','status','featured','page_layout');
        if($params['featured'] ===true){
                $query->where('featured','=',2);
        }else{
                //$query->where('featured','!=',2);
        }
        if($params['retrieving_results'] =='first'){
            return $query->whereIn('status',$params['status'])
                     ->where('page_layout','=',$params['page_layout'])
                     //->with('createdBy','updatedBy')
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     ->first();
        }else{

            return $query->whereIn('status',$params['status'])
                     ->where('page_layout','=',$params['page_layout'])
                     //->with('createdBy','updatedBy')
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     //->toSql();
                     ->get();

        }

    }

   public function scopeFrontList($query,$params){

        //$result = array();
        //dd($params);
        $query->select('id','title','short_description AS description','slug','hit','created_at','status','featured','updated_at');
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
        $date = date('Y-m-d H:i:s');
        $query->select('id','title','short_description AS description','slug','hit','created_at','status');
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

 

    public function setUrlnewsAttribute($id){
        $lang = \App::getLocale();
        $segment = \Request::segment(2);
        //article-detail
        return $this->attributes['urlnews'] =  route('news-detail',\Hashids::encode($id));
    }

    public function scopeDetail($query,$params){
        $lang = $this->getLang();
            $query->select('id','title','description','hit','created_at','status','meta_title','meta_keywords','meta_description','page_layout');
            $query->where('slug','=',$params['slug']);

        return $query->first();
    }

    public function scopeDetailId($query,$params){
        $lang = $this->getLang();
            $query->select('id','title','description','hit','created_at','updated_at','status','meta_title','meta_keywords','meta_description');
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
