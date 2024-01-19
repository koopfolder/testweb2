<?php

namespace App\Modules\Dashboard\Models;

use Illuminate\Database\Eloquent\Model;


class Modules extends Model  
{

    protected $table = 'module';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'status'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    public function scopeData($query,$params)
    {
        $query->select('id','name');
        return $query->whereIn('status',$params['status'])
                     ->get();
    }



}
