<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class MediaHitLogs extends Model
{
    protected $table = 'media_hit_logs';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'media_id',
        'token'
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
        $query->select('id','title');
        return $query->get();
    }

    public function scopeDataId($query,$params)
    {   
        $lang = \App::getLocale();
        $query->select('id');
        return $query->where('media_id',$params['id'])
                     ->where('token',$params['token'])
                     ->first();
    }


}
