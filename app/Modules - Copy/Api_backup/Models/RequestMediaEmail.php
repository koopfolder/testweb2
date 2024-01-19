<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class RequestMediaEmail extends Model
{
    protected $table = 'request_media_email';
    protected $primaryKey = 'id';

    protected $fillable = [
        'email',
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

        $query->select('id','email','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params['status'])->with('createdBy','updatedBy')
            ->orderBy('created_at','DESC')
            //->toSql();
            ->get();
    }


    public function scopeEmail($query,$params)
    {
        $query->select('email');
        return $query->whereIn('status',$params['status'])
               ->get();
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
