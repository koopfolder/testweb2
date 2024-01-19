<?php

namespace App\Modules\Exhibition\Models;

use Illuminate\Database\Eloquent\Model;

class BookAnExhibition extends Model {


    protected $table = 'book_an_exhibition';
    protected $primaryKey = 'id';


    protected $fillable = [

        'name',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'exhibition_id',
        'email',
        'phone',
        'description',
        'user_type',
        'user_other',
        'age',
        'agency_name',
        'agency_address',
        'tel',
        'coordinator_name',
        'coordinator_position',
        'objective',

    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    public function scopeData($query,$params)
    {   
        $query->select('id','name','email','phone','description','exhibition_id','created_at');
        return $query->whereIn('status',$params['status'])
                    ->with('exhibition')
                    ->orderBy('created_at','desc')
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


    public function exhibition()
    {
        return $this->belongsTo('App\Modules\Exhibition\Models\Exhibition','exhibition_id','id')->select('title','id');
    }


}
