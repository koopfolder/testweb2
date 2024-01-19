<?php

namespace App\Modules\Franchise\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'tbl_province';
    protected $primaryKey = 'id';

    protected $fillable = [
        'province_code',
        'province_name_th',
        'province_name_en',
        'geo_id'
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
        $query->select('province_code','province_name_th AS province_name');
        return $query->where($params['field'], 'like', '%'.$params['val'].'%')
            //->toSql();
            ->first();
    }

    public function scopeDataList($query,$params)
    {
        $query->select('province_code','province_name_th AS province_name');
        $query->orderByRaw('CONVERT (province_name_th USING tis620) ASC');
        return $query->get();
    }


}
