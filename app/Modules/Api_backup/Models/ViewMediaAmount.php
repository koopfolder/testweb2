<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class ViewMediaAmount extends Model
{
    protected $table = 'view_media_amount';
    protected $primaryKey = 'id';

    protected $fillable = [
        'department_id',
        'total'
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
        $query->select('department_id','total');
        return $query->get();
    }


   
}
