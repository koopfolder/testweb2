<?php

namespace App\Modules\Article\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class ArticleCategory extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    protected $table = 'article_category';
    protected $primaryKey = 'id';


    protected $fillable = [
        'title',
        'description',
        'status',
        'featured',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'short_description',
        'type'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    public function scopeData($query,$params)
    {   
        $lang = \App::getLocale();
        // if($lang === 'th'){
        //     $query->select('year','description_th AS description','created_at','created_by','updated_by');
        // }else{
        //     $query->select('year','description_en AS description','created_at','created_by','updated_by');
        // }
        $query->select('id','title','description','created_at','created_by','updated_by','status');
        //$query->whereIn('status',$params)->with('createdBy','updatedBy');

        //dd($query->toSql());
        return $query->whereIn('status',$params['status'])->with('createdBy','updatedBy')->get();
    }

    public function scopeDataDropdown($query,$params)
    {   
        $lang = \App::getLocale();
        // if($lang === 'th'){
        //     $query->select('year','description_th AS description','created_at','created_by','updated_by');
        // }else{
        //     $query->select('year','description_en AS description','created_at','created_by','updated_by');
        // }
        $query->select('id','title');
        //$query->whereIn('status',$params)->with('createdBy','updatedBy');

        //dd($query->toSql());
        return $query->whereIn('status',$params['status'])
                     ->where('type',$params['type'])
                     ->with('createdBy','updatedBy')->get();
    }


    public function scopeFrontList($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();
        $query->select('id','title');
        return $query->where('status','publish')
                     ->where('type','health-literacy')
                     ->orderByRaw('id ASC')
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
    }

}
