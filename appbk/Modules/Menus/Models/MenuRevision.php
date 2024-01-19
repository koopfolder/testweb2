<?php

namespace App\Modules\Menus\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class MenuRevision extends Model implements HasMediaConversions
{

    use Sluggable, HasMediaTrait;

    protected $table = 'menu_revisions';

    protected $fillable = [
        'user_id',
        'parent_id',
        'menu_id',
        'site',
        'name',
        'slug',
        'link_type',
        'url_external',
        'description',
        'target',
        'order',
        'classes',
        'layout',
        'layout_params',
        'module_slug',
        'module_ids',
        'status',
        'position',
        'meta_title',
        'meta_keywords',
        'meta_description'
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
        return $this->belongsTo('App\Modules\Menus\Models\Menu', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Modules\Menus\Models\Menu', 'parent_id');
    }

    public function registerMediaConversions()
    {

        $this
            ->addMediaConversion('thumb1366x635')
            ->width(1366)
            ->height(635)
            ->performOnCollections('image_desktop');

        $this
            ->addMediaConversion('thumb1024x618')
            ->width(1024)
            ->height(618)
            ->performOnCollections('image_desktop');

        $this
            ->addMediaConversion('thumb560x338')
            ->width(560)
            ->height(338)
            ->performOnCollections('image_desktop');

        $this
            ->addMediaConversion('thumb270x168')
            ->width(270)
            ->height(168)
            ->performOnCollections('image_desktop');

        $this
            ->addMediaConversion('thumb200x200')
            ->width(200)
            ->height(200)
            ->performOnCollections('image_desktop');

        $this
            ->addMediaConversion('thumb226x127')
            ->width(226)
            ->height(127)
            ->performOnCollections('image_desktop');

        $this
            ->addMediaConversion('thumb133x80')
            ->width(133)
            ->height(80)
            ->performOnCollections('image_desktop');

        /*End desktop */
    
    }



}
