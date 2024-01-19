<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class ListTarget extends Model
{
    protected $table = 'list_target';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'target_id',
        'status',
        'created_at',
        'updated_at', 
        'created_by',
        'updated_by',
        'parent_id',
        'TargetGuoupID',
        'order'

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
        $query->select('id','name AS title','target_id','created_at','created_by','updated_by','status','TargetGuoupID');
        return $query->whereIn('status',$params['status'])->with('createdBy','updatedBy')
            ->orderBy('TargetGuoupID','DESC')
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


    public function children()
    {
        return $this->hasMany('App\Modules\Api\Models\ListTarget', 'parent_id')->where('status','publish');
    }
   
}
