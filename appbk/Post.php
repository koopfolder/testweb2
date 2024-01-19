<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Cviebrock\EloquentTaggable\Taggable;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Post extends Model implements HasMediaConversions
{
    use Sluggable, Taggable, HasMediaTrait;

    protected $fillable = [
        'user_id', 
        'title',
        'title_en', 
        'slug', 
        'excerpt',
        'excerpt_en', 
        'content',
        'content_en', 
        'type', 
        'start_published', 
        'end_published', 
        'parent_id', 
        'order', 
        'status', 
        'view',
        'effect',
        'pin',
        'is_home',
        'meta_title',
        'meta_keywords', 
        'meta_description', 
        'revision_id', 
        'layout',
        'link',
        'link_type',
        'video',
        'banners',
        'caption1',
        'caption2',
        'category_product',
        'use_for_home'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category');
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
