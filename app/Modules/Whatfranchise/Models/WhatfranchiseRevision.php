<?php

namespace App\Modules\Whatfranchise\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class WhatfranchiseRevision extends Model implements HasMediaConversions
{
    use Sluggable, HasMediaTrait;

    protected $table = 'tbl_news_revisions';
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
        'updated_by',
        'news_id'
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
            $query->select('id','title_th AS title','description_th AS description','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params)->with('createdBy','updatedBy')->get();
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
            ->performOnCollections('desktop');

        $this
            ->addMediaConversion('thumb1024x618')
            ->width(1024)
            ->height(618)
            ->performOnCollections('desktop');

            $this
            ->addMediaConversion('thumb560x338')
            ->width(560)
            ->height(338)
            ->performOnCollections('desktop');

            $this
            ->addMediaConversion('thumb270x168')
            ->width(270)
            ->height(168)
            ->performOnCollections('desktop');

            $this
            ->addMediaConversion('thumb200x200')
            ->width(200)
            ->height(200)
            ->performOnCollections('desktop');

            $this
                ->addMediaConversion('thumb226x127')
                ->width(226)
                ->height(127)
                ->performOnCollections('desktop');

            $this
            ->addMediaConversion('thumb133x80')
            ->width(133)
            ->height(80)
            ->performOnCollections('desktop');
    }
}
