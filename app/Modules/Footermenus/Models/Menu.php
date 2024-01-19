<?php

namespace App\Modules\Footermenus\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Menu extends Model implements HasMediaConversions
{

    use Sluggable, HasMediaTrait;

    protected $table = 'menus';

    protected $fillable = [
        'user_id',
        'parent_id',
        'site',
        'name',
        'slug',
        'link_type',
        'url_external',
        'description',
        'target',
        'order',
        'layout',
        'status',
        'position',
        'layout_params',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'make_reservation'
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

    public function FrontChildren()
    {
        $lang = \App::getLocale();
        if($lang =='th'){
            $data = $this->hasMany('App\Modules\Menus\Models\Menu', 'parent_id')->select('id',
                                                                                          'name_th AS name',
                                                                                          'slug_th AS slug',
                                                                                          'url_external',
                                                                                          'url_internal',
                                                                                          'link_type',
                                                                                          'target',
                                                                                          'status',
                                                                                          'position',
                                                                                          'site',
                                                                                          'parent_id',
                                                                                          'order'
                                                                                          );
        }else{
            $data = $this->hasMany('App\Modules\Menus\Models\Menu', 'parent_id')->select('id',
                                                                                          'name_en AS name',
                                                                                          'slug_en AS slug',
                                                                                          'url_external',
                                                                                          'url_internal',
                                                                                          'link_type',
                                                                                          'target',
                                                                                          'status',
                                                                                          'position',
                                                                                          'site',
                                                                                          'parent_id',
                                                                                          'order'
                                                                                          );
        }
        return $data;
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
