<?php

namespace App\Modules\Banner\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class BannerCategory extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    protected $table = 'banner_category';

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status'
    ];


    public function banners()
    {
        return $this->hasMany('App\Modules\Banner\Models\Banner', 'category_id');
    }

    public function scopeDataDropdown($query){

        $result = array();
        $data = $query->select('id','name')
                                ->where('status','=','publish')
                                ->get();
        $data = collect($data);
        if($data->count() > 0){
            foreach ($data as $key => $value) {
                $result[$value->id] = $value->name;
            }
        }
        return $result;
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
