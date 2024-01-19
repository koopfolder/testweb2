<?php

namespace App\Modules\Franchise\Models;

use Illuminate\Database\Eloquent\Model;

class FranchiseCategoryRevision extends Model 
{

    protected $table = 'tbl_franchise_category_revisions';
    protected $primaryKey = 'id';


    protected $fillable = [
        'category_name',
        'description',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'category_id'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    public function scopeData($query,$params)
    {   
        $query->select('id','category_name','description','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params)->with('createdBy','updatedBy')->get();
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
