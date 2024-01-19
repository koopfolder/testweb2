<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class ListMediaIssues extends Model
{
    protected $table = 'list_media_issues';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'media_id',
        'issues_id'
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
