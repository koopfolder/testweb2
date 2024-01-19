<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentTaggable\Taggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
// use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Category extends Model implements HasMediaConversions
{

    use Sluggable, Taggable, HasMediaTrait;

    protected $table = 'categories';

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

    protected $fillable = [
        'title', 'slug', 'summary', 'status', 'user_id', 'parent_id', 'module', 'order'
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Category', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Category', 'parent_id');
    }

    public function posts()
    {
        return $this->belongsToMany('App\Post')->using('App\PostCategory');
    }

    public function registerMediaConversions()
    {
        $this
            ->addMediaConversion('desktop')
            ->width(150)
            ->height(150)
            ->performOnCollections('avatar');
    }

}
