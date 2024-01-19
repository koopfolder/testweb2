<?php

namespace App\Modules\Experienceawards\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Article extends Model implements HasMediaConversions
{
    use HasMediaTrait;

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
        'meta_keywords',
        'meta_description',
        'hit',
        'new',
        'date_event',
        'page_layout',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'order'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */


    public function scopeData($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();
        // if($lang === 'th'){
        //     $query->select('year','description_th AS description','created_at','created_by','updated_by');
        // }else{
        //     $query->select('year','description_en AS description','created_at','created_by','updated_by');
        // }
            $query->select('id','title_th AS title','description_th AS description','created_at','created_by','updated_by','status','order');
        return $query->whereIn('status',$params['status'])->where('page_layout','=',$params['page_layout'])->with('createdBy','updatedBy')
            ->orderBy('order','ASC')
            //->toSql();
            ->get();
    }


    public function setUrlAttribute($id){
        $lang = \App::getLocale();
        $segment = \Request::segment(2);
        //dd($segment,$lang);
        return $this->attributes['url'] = \Hashids::encode($id);
    }


    public function setUrlawardsAttribute($id){
        $lang = \App::getLocale();
        $segment = \Request::segment(2);
        //dd($segment,$lang);
        return $this->attributes['urlawards'] = ($lang =='th' ? route($segment.'-รางวัลของบริษัท',\Hashids::encode($id)):route($segment.'-awards',\Hashids::encode($id)));
    }

    // public function setUrlAttribute($id){
    //     return $this->attributes['url'] = Route('news-detail',\Hashids::encode($id));
    // }

    public function scopeFrontData($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();
        // if($lang === 'th'){
        //     $query->select('id','title_th AS title','description_th AS description');
        // }else{
        //     $query->select('id','title_en AS title','description_en AS description');
        // }
        $query->select('id','title_th','title_en','description_en','description_th');
        return $query
                     ->where('status','=','publish')
                     ->where('page_layout','=',$params['page_layout'])
                     ->orderBy('order','ASC')
                     //->get();
                     ->paginate(6);
    }

    public function scopeFrontDataHighlight($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();
        // if($lang === 'th'){
        //     $query->select('id','title_th AS title','description_th AS description');
        // }else{
        //     $query->select('id','title_en AS title','description_en AS description');
        // }
        $query->select('id','title_th','title_en','description_en','description_th','order','featured');
        return $query
                     ->where('status','=','publish')
                     ->where('page_layout','=',$params['page_layout'])
                     ->orderByRaw('featured DESC ,`order` ASC')
                     ->limit(10)
                     ->get();
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
