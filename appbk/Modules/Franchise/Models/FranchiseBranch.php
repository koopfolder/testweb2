<?php

namespace App\Modules\Franchise\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use File;
class FranchiseBranch extends Model  
{
    protected $table = 'tbl_franchise_branch';
    protected $primaryKey = 'id';

    protected $fillable = [
        'address',
        'province',
        'district',
        'subdistrict',
        'zipcode',
        'franchise_id'
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
        $result = array();
        
        $query->select('id','address','province','district','subdistrict','zipcode','franchise_id');
        $data =$query->where('franchise_id',$params['franchise_id'])
                     ->orderBy('created_at','DESC')
                     //->toSql();
                     ->get();
        //dd(collect($data)->count());
        if(collect($data)->count() > 0){
                //dd(file_exists(public_path('files/franchise/json/').'location_'.$params['franchise_id'].".json"));
                if(file_exists(public_path('files/franchise/json/').'location_'.$params['franchise_id'].".json")){
                    $location = File::get(public_path('files/franchise/json/').'location_'.$params['franchise_id'].".json");
                    $location = collect(json_decode($location));
                }else{
                    $location = '';
                }
                //dd(empty($location));
            foreach ($data as $key => $value) {
                $val =array();
                $province = Province::Data(['field'=>'province_code','val'=>$value->province]);
                $province_name = (collect($province)->sum() ? $province->province_name:'');
                $district = District::Data(['field'=>'district_code','val'=>$value->district,'province_code'=>(collect($province)->sum() ? $province->province_code:'')]);
                $district_name = (collect($district)->sum() ? $district->district_name:'');
                $subdistrict = Subdistrict::Data(['field'=>'subdistrict_code','val'=>$value->subdistrict,'province_code'=>(collect($province)->sum() ? $province->province_code:''),'district_code'=>(collect($district)->sum() ? $district->district_code:'')]);
                $subdistrict_name = (collect($subdistrict)->sum() ? $subdistrict->subdistrict_name:'');
                //dd($value->id);
                $val['id'] = $value->id;
                $val['address'] = $value->address;
                $val['province'] = str_replace(" ","", $province_name);
                $val['district'] = str_replace(" ","", $district_name);
                $val['subdistrict'] = str_replace(" ","", $subdistrict_name);
                $val['zipcode'] = $value->zipcode;
                $val['franchise_id'] = $value->franchise_id;
                $val['latitude'] = ($location !='' ? $location->where('branch_id',$value->id)->first()->latitude:'');
                $val['longitude']= ($location !='' ? $location->where('branch_id',$value->id)->first()->longitude:'');

                array_push($result,$val);
                //dd($val);
            }
        }else{
            $result = 'false';
        }
        //dd($result);
        return $result;
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
