<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assigned_roles extends Model
{

    public function scopePermissions($query,$params)
    {
        $query->select('role_id','entity_id');
        return $query->where('entity_id','=',$params['user_id'])->with('role')->first();
    }

    public function role()
    {
        return $this->belongsTo('Silber\Bouncer\Database\Role','role_id','id')->select('name','id','permissions_data');
    }
    
}
