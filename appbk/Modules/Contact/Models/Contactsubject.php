<?php

namespace App\Modules\Contact\Models;

use Illuminate\Database\Eloquent\Model;

class Contactsubject extends Model
{

    protected $table = 'contact_us_subject';
    protected $primaryKey = 'id';


    protected $fillable = [
        'title',
        'email',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    public function getLang(){
        return  \App::getLocale();
    }

    public function scopeData($query,$params)
    {   
        $lang = \App::getLocale();
        // if($lang === 'th'){
        //     $query->select('year','description_th AS description','created_at','created_by','updated_by');
        // }else{
        //     $query->select('year','description_en AS description','created_at','created_by','updated_by');
        // }
            $query->select('id','title','email','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params)->with('createdBy','updatedBy')
                        ->orderBy('created_at','DESC')
                        //->toSql();
                        ->get();

    }

    public function scopeDataDropdown($query,$params){
        $lang = $this->getLang();
        $result = array();
        $query->select('id','title');
        
        $data = $query->whereIn('status',$params)->get();
        $data = collect($data);
        if($data->count() > 0){
            foreach ($data as $key => $value) {
                $result[$value->id] = $value->title;
            }
        }
        return  $result;
    }



    public function createdBy()
    {
        return $this->belongsTo('App\User','created_by','id')->select('name','id');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User','updated_by','id')->select('name','id');
    }
}
