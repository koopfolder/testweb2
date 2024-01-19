<?php

namespace App\Modules\Joinus\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;


class Careers extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    protected $table = 'careers';
    protected $primaryKey = 'id';


    protected $fillable = [
        'position_th',
        'position_en',
        'job_description_th',
        'job_description_en',
        'status',
        'qualifications_th',
        'qualifications_en',
        'amount',
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
            $query->select('*');
        return $query->whereIn('status',$params)->with('createdBy','updatedBy')
                     ->orderBy('created_at','DESC')
                     //->toSql();
                     ->get();
    }

    public function scopeDetail($query,$params){
        $lang = \App::getLocale();
        if($lang === 'th'){
            $query->select('id','position_th AS position');
        }else{
            $query->select('id','position_en AS position');
        }
        return $query
                    ->where('status','publish')
                    ->where('id','=',$params['id'])
                    ->first();

    }

    public function setUrlAttribute($id){
        $lang = \App::getLocale();
        $segment = \Request::segment(2);
        return $this->attributes['url'] =  ($lang =='th' ? route($segment.'-สมัครงาน',\Hashids::encode($id)):route($segment.'-apply-job',\Hashids::encode($id)));
    }


    public function scopeFrontData($query,$params){
        $lang = \App::getLocale();
        // if($lang === 'th'){
        //     $query->select('id','position_th AS position','amount','job_description_th AS job_description','qualifications_th AS qualifications');
        // }else{
        //     $query->select('id','position_en AS position','amount','job_description_en AS job_description','qualifications_en AS qualifications');
        // }
            $query->select('id','position_th','position_en','amount','job_description_th','job_description_en','qualifications_th','qualifications_en');

        return $query->where('status','=',$params['status'])->get();
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
            ->addMediaConversion('thumb1366x635')
            ->width(1366)
            ->height(635)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb1024x618')
            ->width(1024)
            ->height(618)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb560x338')
            ->width(560)
            ->height(338)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb270x168')
            ->width(270)
            ->height(168)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb200x200')
            ->width(200)
            ->height(200)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb226x127')
            ->width(226)
            ->height(127)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb133x80')
            ->width(133)
            ->height(80)
            ->performOnCollections('cover_desktop');

        /*End desktop */

        $this
            ->addMediaConversion('thumb1366x768px')
            ->width(1366)
            ->height(768)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb1024x618')
            ->width(1024)
            ->height(618)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb560x338')
            ->width(560)
            ->height(338)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb270x168')
            ->width(270)
            ->height(168)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb200x200')
            ->width(200)
            ->height(200)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb226x127')
            ->width(226)
            ->height(127)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb133x80')
            ->width(133)
            ->height(80)
            ->performOnCollections('gallery_desktop');

        /*End gallery_desktop*/
    }

}
