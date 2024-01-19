<?php

namespace App\Modules\Franchise\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'tbl_district';
    protected $primaryKey = 'id';

    protected $fillable = [
        'district_code',
        'district_name_th',
        'district_name_en',
        'geo_id',
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
        $query->select('district_code','district_name_th AS district_name','province_code');
        return $query->where($params['field'], 'like', '%'.$params['val'].'%')
               ->where('province_code',$params['province_code'])
            //->toSql();
            ->first();
    }

    public function scopeDataList($query,$params)
    {
        $query->select('district_code','district_name_th AS district_name','province_code');
        $query->orderByRaw('CONVERT (district_name_th USING tis620) ASC');
        return $query->get();
    }


}
