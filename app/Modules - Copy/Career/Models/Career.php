<?php

namespace App\Modules\Career\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Career extends Model
{

    protected $fillable = [
        'name',
        'file_path',
        'status'
    ];

    public function registerMediaConversions()
    {

        $this
            ->addMediaConversion('thumb1366x768')
            ->width(1366)
            ->height(768)
            ->performOnCollections('icon_desktop');

        $this
            ->addMediaConversion('thumb768xauto')
            ->width(768)
            ->height(768)
            ->performOnCollections('icon_mobile');

        /*End addMediaConversion TH*/

        // $this
        //     ->addMediaConversion('thumb1366x768')
        //     ->width(1366)
        //     ->height(768)
        //     ->performOnCollections('desktop_en');

        // $this
        //     ->addMediaConversion('thumb1366x768')
        //     ->width(1366)
        //     ->height(768)
        //     ->performOnCollections('mobile_en');

        /*End addMediaConversion EN*/


    }
}
