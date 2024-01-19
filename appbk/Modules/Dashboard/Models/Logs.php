<?php

namespace App\Modules\Dashboard\Models;

use Illuminate\Database\Eloquent\Model;


class Logs extends Model  
{

    protected $table = 'logs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'event',
        'created_at',
        'created_by',
        'module_id'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    public function scopeReport($query,$params)
    {
        
        $limit = (isset($params['request']['limit']) ? $params['request']['limit']:10);
        //dd($params,$limit);
        $query->select('id','event AS title','updated_at','created_by','module_id');
            
    
        if(isset($params['request']['module_id']) && $params['request']['module_id'] !=0){
            $query->where('module_id', '=',$params['request']['module_id']);
        }

        if((isset($params['request']['start_date']) && $params['request']['start_date'] !='') &&  (isset($params['request']['end_date']) && $params['request']['end_date'] !='')){
            //dd(str_replace("/","-",$params['request']['start_date']));

            $start_date = date("Y-m-d",strtotime(str_replace("/","-",$params['request']['start_date'])));
            $end_date = date("Y-m-d",strtotime(str_replace("/","-",$params['request']['end_date'])));

            //dd($start_date,$end_date);
            $query->where('updated_at','>=',$start_date);
            $query->where('updated_at','<=',$end_date);     
        }
 

        if(isset($params['request']['keyword'])){
            $query->whereRaw('event like "%'.$params['request']['keyword'].'%"');
        }

        $query->orderBy('updated_at', 'desc');
        $query->with('createdBy','moduleName');

        return $query->paginate($limit);

    }



    public function createdBy()
    {
        return $this->belongsTo('App\User','created_by','id')->select('name','id');
    }

    public function moduleName()
    {
        return $this->belongsTo('App\Modules\Dashboard\Models\Modules','module_id','id')->select('name','id');
    }

    

}
