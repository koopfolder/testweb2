<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class RequestMedia extends Model
{
    protected $table = 'request_media';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'phone',
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

        $query->select('id','name','email','phone','description','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params['status'])->with('createdBy','updatedBy')
            ->orderBy('created_at','DESC')
            //->toSql();
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
