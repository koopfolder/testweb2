<?php

namespace App\Modules\Menus\Models;

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

    public function FrontChildren()
    {

        $data = $this->hasMany('App\Modules\Menus\Models\Menu', 'parent_id')->select('id',
                                                                                          'name AS name',
                                                                                          'slug AS slug',
                                                                                          //'description',
                                                                                          'url_external',
                                                                                          'link_type',
                                                                                          'layout',
                                                                                          'layout_params',
                                                                                          'target',
                                                                                          'status',
                                                                                          'position',
                                                                                          'parent_id',
                                                                                          'order'
                                                                                          )
                                                                            ->where('status', 'publish')
                                                                            ->orderBy('order', 'ASC');
        
        return $data;
    }

    public function FrontChildrenCaseRotue()
    {
        $data = $this->hasMany('App\Modules\Menus\Models\Menu', 'parent_id')->select('id',
                                                                                          'slug AS slug',
                                                                                          'link_type',
                                                                                          'layout',
                                                                                          'status',
                                                                                          'position',
                                                                                          'parent_id'
                                                                                          )
                                                                            ->where('status', 'publish')
                                                                            ->orderBy('order', 'ASC');
        
        return $data;
    }

    public function FrontBreadcrumbs()
    {

        $data = $this->hasMany('App\Modules\Menus\Models\Menu', 'parent_id')->select('id',
                                                                                          'name',
                                                                                          'slug',
                                                                                          'link_type',
                                                                                          'status',
                                                                                          'position',
                                                                                          'layout',
                                                                                          'parent_id'
                                                                                          );
        return $data;
    }

    public function scopeSlug($query,$params)
    {
        //dd($params);
            $query->select('id',$params['field'].' AS slug','layout');
        return $query->where('status','publish')->where($params['where'],'=',$params['value'])->first();
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
