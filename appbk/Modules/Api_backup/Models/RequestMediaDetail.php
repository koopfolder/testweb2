<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class RequestMediaDetail extends Model
{
    protected $table = 'request_media_detail';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'description',
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
        return \App::getLocale();
    }


    public function scopeData($query,$params)
    {
        //dd($params);
        $query->select('id','title','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params['status'])->with('createdBy','updatedBy')
            ->orderBy('created_at','DESC')
            //->toSql();
            ->get();
    }

    public function scopeFrontData($query,$params)
    {
        $query->select('id','title','description');
        return $query->whereIn('status',$params['status'])
                     ->orderBy('id', 'desc')
                     ->first();
    }
   

    public function createdBy()
    {
        return $this->belongsTo('App\User','created_by','id')->select('name','id');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User','updated_by','id')->select('name','id');
    }

   
}
