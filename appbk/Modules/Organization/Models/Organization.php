<?php

namespace App\Modules\Organization\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Organization extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    protected $table = 'organization';
    protected $primaryKey = 'id';


    protected $fillable = [
        'id',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    public function scopeData($query,$params)
    {
        $lang = \App::getLocale();
        // if($lang === 'th'){
        //     $query->select('year','description_th AS description','created_at','created_by','updated_by');
        // }else{
        //     $query->select('year','description_en AS description','created_at','created_by','updated_by');
        // }
            $query->select('id','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params)->with('createdBy','updatedBy')->get();
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User','created_by','id')->select('name','id');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User','updated_by','id')->select('name','id');
    }

    public function registerMediaConversions()
    {

        $this
            ->addMediaConversion('thumb1366x768')
            ->width(1366)
            ->height(768)
            ->performOnCollections('desktop_th');

        // $this
        //     ->addMediaConversion('thumb768xauto')
        //     ->width(768)
        //     ->height(768)
        //     ->performOnCollections('mobile_th');

        /*End addMediaConversion TH*/

        $this
            ->addMediaConversion('thumb1366x768')
            ->width(1366)
            ->height(768)
            ->performOnCollections('desktop_en');

        // $this
        //     ->addMediaConversion('thumb1366x768')
        //     ->width(1366)
        //     ->height(768)
        //     ->performOnCollections('mobile_en');

        /*End addMediaConversion EN*/


    }

}
