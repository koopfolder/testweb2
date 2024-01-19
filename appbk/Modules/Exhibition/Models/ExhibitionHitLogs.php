<?php

namespace App\Modules\Exhibition\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitionHitLogs extends Model
{
    protected $table = 'exhibition_hit_logs';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'exhibition_id',
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
        return $query->where('exhibition_id',$params['id'])
                     ->where('token',$params['token'])
                     ->first();
    }


}
