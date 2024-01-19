<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class MediaTags extends Model
{
    protected $table = 'tbl_media_tags';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'media_id',
        'tags_id'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function getLang(){
        return \App::getLocale();
    }

    public function scopeData($query,$params)
    {
        //dd($params);
        $lang = $this->getLang();
        // if($lang === 'th'){
        //     $query->select('year','description_th AS description','created_at','created_by','updated_by');
        // }else{
        //     $query->select('year','description_en AS description','created_at','created_by','updated_by');
        // }
            $query->select('id','name AS title','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params['status'])->with('createdBy','updatedBy')
            ->orderBy('created_at','DESC')
            //->toSql();
            ->get();
    }


    public function scopeDataId($query,$params)
    {   
        $lang = \App::getLocale();
        $query->select('tags_id');
        return $query->where('media_id',$params['media_id'])->get();
    }
   
    public function scopeDataMediaId($query,$params)
    {   
        $lang = \App::getLocale();
        $query->select('media_id');
        return $query
                ->whereIn('tags_id',$params['tags_select'])
                ->where('media_id','!=',$params['media_id'])
                ->get();
    }
   

    public function createdBy()
    {
        return $this->belongsTo('App\User','created_by','id')->select('name','id');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User','updated_by','id')->select('name','id');
    }


    public function children()
    {
        return $this->hasMany('App\Modules\Api\Models\ListIssue', 'parent_id')->where('status','publish');
    }

   
}
