<?php

namespace App\Modules\Manager\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Categories extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    protected $table = 'board_and_management_categories';
    protected $primaryKey = 'id';


    protected $fillable = [
        'name_th',
        'name_en',
        'order',
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
            $query->select('id','name_th AS name','order','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params)->with('createdBy','updatedBy')
                     ->orderBy('created_at','DESC')
                     //->toSql();
                     ->get();
    }

    public function scopeFrontData($query,$params){
        $lang = $this->getLang();
        // if($lang ==='th'){
        //     $query->select('id','name_th AS name','order');
        // }else{
        //     $query->select('id','name_en AS name','order');
        // }
            $query->select('id','name_en','name_th','order');
        return $query->where('status','=','publish')->orderBy('order','asc')->get();
    }

    public function scopeDataDropdown($query,$params){
        $lang = $this->getLang();
        $result = array();
        $query->select('id','name_th AS name');
        $data = $query->whereIn('status',$params)->get();
        $data = collect($data);
        if($data->count() > 0){
            foreach ($data as $key => $value) {
                $result[$value->id] = $value->name;
            }
        }
        return  $result;
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
