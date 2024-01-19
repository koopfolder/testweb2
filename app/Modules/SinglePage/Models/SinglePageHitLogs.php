<?php

namespace App\Modules\SinglePage\Models;

use Illuminate\Database\Eloquent\Model;


class SinglePageHitLogs extends Model 
{

    protected $table = 'single_page_hit_logs';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'single_page_id',
        'token',
        'created_at'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function getLang(){
        return \App::getLocale();
    }



    public function scopeDataId($query,$params)
    {   
        $lang = \App::getLocale();
        $query->select('id');
        return $query->where('single_page_id',$params['id'])
                     ->where('token',$params['token'])
                     ->first();
    }



}
