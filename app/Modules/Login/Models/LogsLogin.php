<?php

namespace App\Modules\Login\Models;

use Illuminate\Database\Eloquent\Model;

class LogsLogin extends Model 
{
    protected $table = 'logs_login';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
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
