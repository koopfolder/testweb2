<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLogs extends Model
{
    protected $table = 'api_logs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'api_name',
        'total',
        'page_size',
        'page_no',
        'page_all',
        'note',
        'params',
        'status',
        'api_type',
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
    
        $query->select('id','api_name','params','total','page_size','page_no','page_all','status','note');
        return $query->whereIn('status',$params['status'])
                     ->where('api_type','=',$params['api_type'])
                     ->orderBy('created_at','asc')
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
