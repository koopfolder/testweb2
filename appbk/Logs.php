<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model 
{

    protected $table = 'logs';
    protected $fillable = [
        'event',
        'created_at',
        'created_by',
        'module_id'
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id');
    }


}
