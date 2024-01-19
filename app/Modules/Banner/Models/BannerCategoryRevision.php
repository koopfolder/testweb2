<?php

namespace App\Modules\Banner\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class BannerCategoryRevision extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    protected $table = 'banner_category_revisions';

    protected $fillable = [
        'created_by',
        'updated_by',
        'banner_category_id',
        'name',
        'description',
        'status'
    ];

    public function banners()
    {
        return $this->hasMany('App\Modules\Banner\Models\Banner', 'category_id');
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
