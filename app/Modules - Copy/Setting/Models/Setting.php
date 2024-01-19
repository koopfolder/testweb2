<?php

namespace App\Modules\Setting\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Setting extends Model implements HasMediaConversions
{

    use HasMediaTrait;

    protected $table = 'settings';
    protected $fillable = ['name', 'slug', 'value', 'module', 'status'];


    public function registerMediaConversions()
    {
        $this
            ->addMediaConversion('thumb1366x635')
            ->width(1366)
            ->height(635)
            ->performOnCollections('ncds_cover_image');

        $this
            ->addMediaConversion('thumb1024x618')
            ->width(1024)
            ->height(618)
            ->performOnCollections('ncds_cover_image');

        $this
            ->addMediaConversion('thumb560x338')
            ->width(560)
            ->height(338)
            ->performOnCollections('ncds_cover_image');

        $this
            ->addMediaConversion('thumb270x168')
            ->width(270)
            ->height(168)
            ->performOnCollections('ncds_cover_image');

        $this
            ->addMediaConversion('thumb200x200')
            ->width(200)
            ->height(200)
            ->performOnCollections('ncds_cover_image');

        $this
            ->addMediaConversion('thumb226x127')
            ->width(226)
            ->height(127)
            ->performOnCollections('ncds_cover_image');

        $this
            ->addMediaConversion('thumb133x80')
            ->width(133)
            ->height(80)
            ->performOnCollections('ncds_cover_image');

        /*End desktop */


    }


}

