<?php

namespace App\Connection;

use Illuminate\Database\Eloquent\Model;

class ConnectionAuthority extends Model
{
    protected $table = 'connection_authority';

    protected $fillable = 
    [
        'client_id', 
        'client_company_name', 
        'client_password', 
        'client_status', 
        'created_at',
        'updated_at'
    ];
}
