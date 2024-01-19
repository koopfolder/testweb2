<?php

namespace App\Modules\Franchise\Models;

use Illuminate\Database\Eloquent\Model;

class FranchiseCategory extends Model 
{
    protected $table = 'tbl_franchise_category';
    protected $primaryKey = 'id';

    protected $fillable = [
        'category_name',
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

    public function scopeData($query,$params)
    {
        //dd($params);
        $query->select('id','category_name','description','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params['status'])->with('createdBy','updatedBy')
            ->orderBy('created_at','DESC')
            //->toSql();
            ->get();
    }


    public function scopeDetail($query,$params){
        $lang = $this->getLang();
        // if($lang =='th'){
        //     $query->select('id','title_th AS title','description_th AS description','created_at','status','date_event');
        // }else{
        //     $query->select('id','title_en AS title','description_en AS description','created_at','status','date_event');
        // }
            $query->select('id','title_en','title_th','description_en','description_th','created_at','status','date_event');
            $query->where('id','=',$params['id']);

        return $query->first();
    }

    public function scopeDetailId($query,$params){
        //dd($params['category_name']);
        $query->select('id','category_name');
        $query->where('category_name','like', '%'.$params['category_name'].'%');
        return  $query->first();
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
