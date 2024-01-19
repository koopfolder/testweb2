<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class IcbDolLog extends Model
{
    protected $table = 'ibc_dol_import_log';
    protected $primaryKey = false;
    const UPDATED_AT = false;
    protected $fillable = [
        'UploadFileID',
        'create_at',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */




   
}
