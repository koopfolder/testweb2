<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class ViewAjaxMedia extends Model
{
    protected $table = 'view_ajax_media';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'id'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */


   
}
