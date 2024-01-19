<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class ListMediaKeywords extends Model
{
    protected $table = 'list_media_keywords';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'media_id',
        'keyword'
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
