<?php

namespace App\Modules\Login\Models;

use Illuminate\Database\Eloquent\Model;

class LogsPdpa extends Model 
{
    protected $table = 'logs_pdpa';
    protected $primaryKey = 'id';

    protected $fillable = [
        'data',
        'created_at',
        'updated_at', 
        'created_by',
        'updated_by',
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
