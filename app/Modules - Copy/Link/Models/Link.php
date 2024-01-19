<?php

namespace App\Modules\Link\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Link extends Model implements HasMediaConversions
{

    use Sluggable, HasMediaTrait;

    protected $fillable = [
        'user_id',
        'parent_id',
        'site',
        'name',
        'slug',
        'link_type',
        'url_internal',
        'url_external',
        'video',
        'description',
        'icon',
        'target',
        'order',
        'classes',
        'layout',
        'module_slug',
        'module_ids',
        'status',
        'position'
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
                'source' => 'name'
            ]
        ];
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Modules\Link\Models\Link', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Modules\Link\Models\Link', 'parent_id');
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
