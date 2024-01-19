<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class ListMediaTargets extends Model
{
    protected $table = 'list_media_targets';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'media_id',
        'target_id'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function getLang(){
        return \App::getLocale();
    }

}
