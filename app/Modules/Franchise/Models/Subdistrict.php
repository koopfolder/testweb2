<?php

namespace App\Modules\Franchise\Models;

use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    protected $table = 'tbl_subdistrict';
    protected $primaryKey = 'id';

    protected $fillable = [
        'subdistrict_code',
        'subdistrict_name_th',
        'subdistrict_name_en',
        'geo_id',
        'district_id',
        'district_code',
        'province_id',
        'province_code'
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
        $query->select('subdistrict_code','district_code','subdistrict_name_th AS subdistrict_name','province_code');
        return $query->where($params['field'], 'like', '%'.$params['val'].'%')
               ->where('province_code',$params['province_code'])
               ->where('district_code',$params['district_code'])
            //->toSql();
            ->first();
    }

    public function scopeDataList($query,$params)
    {
        $query->select('subdistrict_code','district_code','subdistrict_name_th AS subdistrict_name','province_code');
        $query->orderByRaw('CONVERT (subdistrict_name_th USING tis620) ASC');
        return $query->get();
    }


}
