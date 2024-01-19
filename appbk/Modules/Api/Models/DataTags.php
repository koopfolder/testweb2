<?php

namespace App\Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;

class DataTags extends Model
{
    protected $table = 'tbl_data_tags';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'data_id',
        'tags_id',
        'data_type'
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
        return $query->where('data_id',$params['data_id'])
                     ->where('data_type','=',$params['data_type'])
                     ->get();
    }

    public function scopeDataIdFront($query,$params)
    {   
        $lang = \App::getLocale();
        $query->select('tags_id');
        return $query->where('data_id',$params['data_id'])
                     ->whereIn('data_type',['media','article'])
                     //->limit(10)
                     ->get();
    }

   
    public function scopeDataMediaId($query,$params)
    {   
        $lang = \App::getLocale();
        $query->select('data_id','data_type');
        return $query
                ->whereIn('tags_id',$params['tags_select'])
                ->where('data_id','!=',$params['data_id'])
                //->limit(10)
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
