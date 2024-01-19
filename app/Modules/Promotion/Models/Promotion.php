<?php

namespace App\Modules\Promotion\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Cviebrock\EloquentTaggable\Taggable;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Promotion extends Model implements HasMediaConversions
{
    use Sluggable, Taggable, HasMediaTrait;

    protected $fillable = [
        'name',
        'slug', 
        'description', 
        'status',
        'link',
        'meta_title', 
        'meta_keywords', 
        'meta_description',
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
