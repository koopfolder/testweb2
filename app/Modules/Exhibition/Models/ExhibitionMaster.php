<?php

namespace App\Modules\Exhibition\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitionMaster extends Model 
{
  
    protected $table = 'exhibition_master';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'status',
        'description',
        'parent_id',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'order'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    public function scopeData($query,$params)
    {   
        $query->select('id','title','parent_id','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params['status'])
                    ->with('createdBy','updatedBy')
                    ->orderBy('created_at','desc')
                    ->get();
    }


    public function scopeDataExhibitionJoin($query,$params)
    {   
        $query->select('exhibition_join');
        return $query->where('status','=','publish')
                     ->where('id','=',$params['id'])
                     ->first();
    }



    public function scopeFrontData($query,$params)
    {   

        //dd($params)
        $query->select('id','title','file_name','file_path','url_external','slug');
        if($params['retrieving_results'] =='first'){
            return $query->whereIn('status',$params['status'])
                     //->with('createdBy','updatedBy')
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     ->first();
        }else{
            return $query->whereIn('status',$params['status'])
                     //->with('createdBy','updatedBy')
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     //->toSql();
                     ->get();

        }

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
        return $this->hasMany('App\Modules\Exhibition\Models\ExhibitionMaster', 'parent_id');
    }


 
}
