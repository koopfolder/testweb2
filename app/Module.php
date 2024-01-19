<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Module extends Model 
{

    protected $table = 'module';
    protected $fillable = [
        'name',
        'status',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id');
    }


}
