<?php

namespace App\Modules\Contact\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['name', 'email', 'message', 'status','subject_id'];


    public function subject()
    {
        return $this->belongsTo('App\Modules\Contact\Models\Contactsubject','subject_id','id')->select('title','id');
    }

}
