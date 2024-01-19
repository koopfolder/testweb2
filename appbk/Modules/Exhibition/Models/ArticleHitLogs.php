<?php

namespace App\Modules\Exhibition\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleHitLogs extends Model
{
    protected $table = 'article_hit_logs';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'article_id',
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
        return $query->where('article_id',$params['id'])
                     ->where('token',$params['token'])
                     ->first();
    }


}
