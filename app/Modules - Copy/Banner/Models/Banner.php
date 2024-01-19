<?php

namespace App\Modules\Banner\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Banner extends Model implements HasMediaConversions
{
    use HasMediaTrait;
    protected $table = 'banners';
    protected $fillable = [
        'category_id',
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
        return $this->belongsTo('App\Modules\Banner\Models\BannerCategory','category_id','id');
    }

    public function scopeData($query,$params){

        $lang = \App::getLocale();
            $query->select(
                            'id',
                            'category_id',
                            'name',
                            'use_content_params',
                            'use_content',
                            'link',
                            'status',
                            'description',
                            'created_at'
                        );
 
            $query->where('status','=','publish');
            $query->where('category_id','=',$params['category']);
            $query->with('category');
        if($params['case_query'] =='random'){
            $query->inRandomOrder();
        }else{
            $query->orderBy('created_at','desc');
        }
        if($params['retrieving_results'] =='first'){
          return  $query->first();
        }else{
          return  $query->get();
        }
      
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
