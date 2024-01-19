<?php

namespace App\Modules\Activities\Models;

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
        'title_th',
        'title_en',
        'slug',
        'description_th',
        'description_en',
        'featured',
        'status',
        'meta_title',
        'date_event',
        'meta_keywords',
        'meta_description',
        'hit',
        'new',
        'page_layout',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'date_event'
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
            $query->select('id','title_th AS title','description_th AS description','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params['status'])->where('page_layout','=',$params['page_layout'])->with('createdBy','updatedBy')
            ->orderBy('created_at','DESC')
            //->toSql();
            ->get();
    }

    public function setUrlAttribute($id){
        $lang = \App::getLocale();
        $segment = \Request::segment(2);
        return $this->attributes['url'] =  ($lang =='th' ? route($segment.'-รายละเอียด',\Hashids::encode($id)):route($segment.'-detail',\Hashids::encode($id)));
    }

    public function scopeDetail($query,$params){
        $lang = $this->getLang();
        // if($lang =='th'){
        //     $query->select('id','title_th AS title','description_th AS description','created_at','status','date_event');
        // }else{
        //     $query->select('id','title_en AS title','description_en AS description','created_at','status','date_event');
        // }
            $query->select('id','title_en','title_th','description_en','description_th','created_at','status','date_event');
            $query->where('id','=',$params['id']);

        return $query->first();
    }

    public function scopeFrontHighlight($query,$params){

        $lang = $this->getLang();
        
        $result = array();
        //dd($params);
        // if($lang === 'th'){
        //     $query->select('id','title_th AS title','description_th AS description','created_at','status','featured','date_event');
        // }else{
        //     $query->select('id','title_en AS title','description_en AS description','created_at','status','featured','date_event');
        // }
            $query->select('id','title_en','title_th','description_en','description_th','created_at','status','featured','date_event');
            $query->where('status','=','publish');
            $query->where('page_layout','=',$params['page_layout']);
                
            $query->orderByRaw('featured DESC ,date_event DESC');
            $data = $query->paginate(8);
            $result['highlight']= true;
            $result['items']= $data;

        return $result;

    }

    public function checkHighlight(){
        $data = Article::select('id')->where('featured','=','2')->count();

        return $data;
    }


    public function scopeFrontData($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();
        if($lang === 'th'){
            $query->select('id','title_th AS title','description_th AS description','created_at','created_by','updated_by','status','featured');
        }else{
            $query->select('id','title_en AS title','description_en AS description','created_at','created_by','updated_by','status','featured');
        }
        if($params['featured'] ===true){
                $query->where('new','=',1);
        }else{
                $query->where('new','!=',1);
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



    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title_th'
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
