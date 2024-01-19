<?php

namespace Silber\Bouncer\Database;

use Illuminate\Database\Eloquent\Model;

class Assigned_roles extends Model
{

    public function __construct(array $attributes = [])
    {
        $this->table = Models::table('assigned_roles');
        parent::__construct($attributes);
    }


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
