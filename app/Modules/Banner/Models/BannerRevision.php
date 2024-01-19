<?php

namespace App\Modules\Banner\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class BannerRevision extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    protected $table = 'banners_revisions';

    protected $fillable = [
        'category_id',
        'banner_id',
        'name',
        'slug',
        'link',
        'use_content_params',
        'use_content',
        'description',
        'video_filename',
        'video_path',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo('App\Modules\Banner\Models\BannerCategory');
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
