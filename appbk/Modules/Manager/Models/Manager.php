<?php

namespace App\Modules\Manager\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Manager extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    protected $table = 'board_and_management';
    protected $primaryKey = 'id';


    protected $fillable = [
        'name_th',
        'name_en',
        'position_th',
        'position_en',
        'categories_id',
        'bord_and_management_type',
        'order',
        'status',
        'education_th',
        'education_en',
        'work_experience_th',
        'work_experience_en',
        'iod_training_th',
        'iod_training_en',
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
    public function getLang(){
        return  \App::getLocale();
    }

    public function scopeData($query,$params)
    {
        $lang = $this->getLang();
        // if($lang === 'th'){
        //     $query->select('year','description_th AS description','created_at','created_by','updated_by');
        // }else{
        //     $query->select('year','description_en AS description','created_at','created_by','updated_by');
        // }
            $query->select('id','name_th AS name','position_th AS position','order','created_at','created_by','updated_by','status','categories_id');
        return $query->whereIn('status',$params)->with('createdBy','updatedBy','categories')
                     ->orderBy('created_at','DESC')
                     //->toSql();
                     ->get();
    }


    public function scopeFrontData($query,$params){
        $lang = $this->getLang();
        // if($lang ==='th'){
        //     $query->select('id','name_th AS name','position_th AS position','order','categories_id','bord_and_management_type');
        // }else{
        //     $query->select('id','name_en AS name','position_en AS position','order','categories_id','bord_and_management_type');
        // }
            $query->select('id','name_th','name_en','position_en','position_th','order','categories_id','bord_and_management_type');
        return $query
                    ->where('status','=','publish')
                    ->where('categories_id','=',$params['categories_id'])
                    ->orderBy('bord_and_management_type','ASC')
                    ->orderBy('order','asc')
                    ->get();
    }

    public function scopeDetail($query,$params){

        $lang = $this->getLang();
        if($lang ==='th'){
            $query->select('id','name_th AS name','position_th AS position','education_th AS education','work_experience_th AS work_experience','iod_training_th AS iod_training');
        }else{
            $query->select('id','name_en AS name','position_en AS position','education_en AS education','work_experience_en AS work_experience','iod_training_en AS iod_training');
        }
        return $query
                    ->where('status','=','publish')
                    ->where('id','=',$params['id'])
                    ->first();

    }

    public function scopeBreadcrumb($query,$params){
        $query->select('id','name_th AS title_th','name_en AS title_en');
        $query->where('id','=',$params['id']);
        return $query->first();
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User','created_by','id')->select('name','id');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User','updated_by','id')->select('name','id');
    }

    public function categories()
    {
        return $this->belongsTo('App\Modules\Manager\Models\Categories','categories_id','id')->select('name_th AS name','id');
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
