<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class Texonomy extends Model
{
    protected $table = 'list_taxonomy';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'taxonomy_id',
        'created_at',
        'update_at'
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
