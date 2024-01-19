<?php

namespace App\Modules\Franchise\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Franchise extends Model implements HasMediaConversions
{
    use Sluggable, HasMediaTrait;
    protected $table = 'tbl_franchise';
    protected $primaryKey = 'id';

    protected $fillable = [
        'brand_name',
        'company_name',
        'category_id',
        'franchise_type',
        'status',
        'description',
        'number_of_branches',
        'lowest_investment_budget',
        'highest_investment_budget',
        'franchise_fee',
        'royalty_fee',
        'contact_address',
        'contact_province',
        'contact_district',
        'contact_subdistrict',
        'contact_zipcode',
        'contact_name',
        'phone',
        'mobile',
        'fax',
        'email',
        'website',
        'line',
        'facebook',
        'youtube',
        'instagram',
        'juristic_person_registration_number',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'created_by',
        'updated_by',
        'date_event',
        'slug',
        'contact_latitude',
        'contact_longitude'
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
        $query->select('id','brand_name AS title','description AS description','created_at','created_by','updated_by','status','slug');
        return $query->whereIn('status',$params['status'])->with('createdBy','updatedBy')
            ->orderBy('created_at','DESC')
            //->toSql();
            ->get();
    }


    public function scopeDetail($query,$params){

        $query->select('id',
                       'brand_name',
                       'company_name',
                       'category_id',
                       'franchise_type',
                       'description',
                       'number_of_branches',
                       'lowest_investment_budget',
                       'highest_investment_budget',
                       'franchise_fee',
                       'royalty_fee',
                       'contact_province',
                       'contact_district',
                       'contact_subdistrict',
                       'contact_zipcode',
                       'contact_latitude',
                       'contact_longitude',
                       'contact_name',
                       'contact_address',
                       'phone',
                       'mobile',
                       'fax',
                       'email',
                       'website',
                       'line',
                       'youtube',
                       'facebook',
                       'instagram',
                       'status',
                       'created_at'
                       );
        $query->where('slug','=',$params['slug']);
        $query->with('category','province','district','subdistrict');

        return $query->first();
    }

    public function scopeFrontList($query,$params){
        
        //dd($params);
        $result = array();
        $query->select('id','brand_name','description','lowest_investment_budget','highest_investment_budget','created_at','status','slug');
        $query->where('status','=','publish');
        
        if(isset($params['params']['brand_name'])){
            $query->where('brand_name', 'like', '%'.$params['params']['brand_name'].'%');
        }

        if(isset($params['params']['category_id']) && $params['params']['category_id'] !=0){
            $query->where('category_id', '=',$params['params']['category_id']);
        }

        if(isset($params['params']['province']) && $params['params']['province'] !=0){
            $query->where('contact_province', '=',$params['params']['province']);
        }

        if(isset($params['params']['district']) && $params['params']['district'] !=0){
            $query->where('contact_district', '=',$params['params']['district']);
        }

        if(isset($params['params']['subdistrict']) && $params['params']['subdistrict'] !=0){
            $query->where('contact_subdistrict', '=',$params['params']['subdistrict']);
        }

        if(isset($params['params']['investment_budget']) && $params['params']['investment_budget'] !=0){
            $investment_budget_ex = explode('-',$params['params']['investment_budget']);
            //dd($investment_budget_ex);
            $query->where('lowest_investment_budget', '>=',$investment_budget_ex[0]);
            $query->where('highest_investment_budget', '<=',$investment_budget_ex[1]);
        }

        $query->orderByRaw('CONVERT (brand_name USING tis620) ASC,created_at DESC');
        $data = $query->paginate(6);
        $result= $data;
       //dd($result,"Test");
        return $result;

    }


    public function scopeFrontCountFranchise($query,$params){

        $result = array();
        $query->select('id');
        $query->where('status','=',$params['status']);
        //$query->get();
        return $query->get()->count();

    }

    public function scopeFrontCountBranch($query,$params){
        
        $result = array();
        $query->selectRaw('SUM(number_of_branches) AS count');
        $query->where('status','=',$params['status']);
        //$query->get();
        return $query->first();

    }


    public function scopeFrontData($query,$params)
    {
        //dd($params);
        $lang = \App::getLocale();
        $query->select('id','brand_name','company_name','category_id','franchise_type','number_of_branches','description','created_at','created_by','updated_by','status','slug');

        if($params['retrieving_results'] =='first'){
            return $query->whereIn('status',$params['status'])
                     ->with('createdBy','updatedBy','category')
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     ->first();
        }else{
            return $query->whereIn('status',$params['status'])
                     ->with('createdBy','updatedBy','category')
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        }
    }



    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'brand_name'
            ]
        ];
    }

    public function category()
    {
        return $this->belongsTo('App\Modules\Franchise\Models\FranchiseCategory','category_id','id')->select('category_name','id');
    }

    public function province()
    {
        return $this->belongsTo('App\Modules\Franchise\Models\Province','contact_province','province_code')->select('province_name_th AS province','province_code');
    }


    public function district()
    {
        return $this->belongsTo('App\Modules\Franchise\Models\District','contact_district','district_code')->select('district_name_th AS district','district_code');
    }

    public function subdistrict()
    {
        return $this->belongsTo('App\Modules\Franchise\Models\Subdistrict','contact_subdistrict','subdistrict_code')->select('subdistrict_name_th AS subdistrict','subdistrict_code');
    }


    public function createdBy()
    {
        return $this->belongsTo('App\User','created_by','id')->select('name','id');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User','updated_by','id')->select('name','id');
    }



    public function registerMediaConversions()
    {
        $this
            ->addMediaConversion('thumb1366x635')
            ->width(1366)
            ->height(635)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb1024x618')
            ->width(1024)
            ->height(618)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb560x338')
            ->width(560)
            ->height(338)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb270x168')
            ->width(270)
            ->height(168)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb200x200')
            ->width(200)
            ->height(200)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb226x127')
            ->width(226)
            ->height(127)
            ->performOnCollections('cover_desktop');

        $this
            ->addMediaConversion('thumb133x80')
            ->width(133)
            ->height(80)
            ->performOnCollections('cover_desktop');

        /*End desktop */

        $this
            ->addMediaConversion('thumb1366x768px')
            ->width(1366)
            ->height(768)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb1024x618')
            ->width(1024)
            ->height(618)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb560x338')
            ->width(560)
            ->height(338)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb270x168')
            ->width(270)
            ->height(168)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb200x200')
            ->width(200)
            ->height(200)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb226x127')
            ->width(226)
            ->height(127)
            ->performOnCollections('gallery_desktop');

        $this
            ->addMediaConversion('thumb133x80')
            ->width(133)
            ->height(80)
            ->performOnCollections('gallery_desktop');

        /*End gallery_desktop*/


        $this
            ->addMediaConversion('thumb1366x635')
            ->width(1366)
            ->height(635)
            ->performOnCollections('logo_desktop');

        $this
            ->addMediaConversion('thumb1024x618')
            ->width(1024)
            ->height(618)
            ->performOnCollections('logo_desktop');

        $this
            ->addMediaConversion('thumb560x338')
            ->width(560)
            ->height(338)
            ->performOnCollections('logo_desktop');

        $this
            ->addMediaConversion('thumb270x168')
            ->width(270)
            ->height(168)
            ->performOnCollections('logo_desktop');

        $this
            ->addMediaConversion('thumb200x200')
            ->width(200)
            ->height(200)
            ->performOnCollections('logo_desktop');

        $this
            ->addMediaConversion('thumb226x127')
            ->width(226)
            ->height(127)
            ->performOnCollections('logo_desktop');

        $this
            ->addMediaConversion('thumb133x80')
            ->width(133)
            ->height(80)
            ->performOnCollections('logo_desktop');

        /*End logo desktop */

    }

}
