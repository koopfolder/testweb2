<?php

namespace App\Modules\Exhibition\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;



class Exhibition extends Model implements HasMediaConversions
{
    use HasMediaTrait,Sluggable;

    protected $table = 'exhibition';
    protected $primaryKey = 'id';


    protected $fillable = [

        'title',
        'file_name',
        'file_type',
        'file_path',
        'description',
        'short_description',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'slug',
        'url_external',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'start_date',
        'end_date',
        'category_id',
        'recommend',
        'hit',
        'rounds',
        'rooms',
        'open_date',


    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    public function scopeData($query,$params)
    {   
        $query->select('id','title','slug','file_path','url_external','category_id','created_at','created_by','updated_by','status');
        return $query->whereIn('status',$params['status'])
                    ->with('createdBy','updatedBy','ExhibitionMaster')
                    ->orderBy('created_at','desc')
                    ->get();
    }


    public function scopeFrontData($query,$params)
    {   

        //dd($params)
        $query->select('id','title','file_name','file_path','url_external','slug');
        if($params['retrieving_results'] =='first'){
            return $query->whereIn('status',$params['status'])
                     //->with('createdBy','updatedBy')
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     ->first();
        }else{
            return $query->whereIn('status',$params['status'])
                     //->with('createdBy','updatedBy')
                     ->limit($params['limit'])
                     ->orderBy('created_at', 'desc')
                     //->toSql();
                     ->get();

        }

    }

    public function scopeFrontListOpenToWatch($query,$params){

        //$result = array();
        //dd($params);
        $date = date('Y-m-d H:i:s');
        $query->select('id','title','short_description AS description','hit','file_path','url_external','slug','created_at','status','updated_at');
        $query->where('status','=','publish');
        
        // if($params['exhibition_join'] !=0){
        //     $query->whereIn('category_id',[$params['category_id'],$params['exhibition_join']]);
        // }else{
        //     $query->where('category_id','=',$params['category_id']);
        // }
        $query->where('category_id','=',$params['category_id']);
        
        $query->where('start_date','<=',$date);
        $query->where('end_date','>=',$date);
        //$query->where('featured','=',"1");
        $query->orderByRaw('created_at DESC');
        $query->limit(5);
        $data = $query->get();
        //$result['items']= $data;
        return $data;

    }


    public function scopeFrontListExhibitionIsClosedToVisitors($query,$params){

        //$result = array();
        //dd($params);
        $date = date('Y-m-d H:i:s');
        $query->select('id','title','short_description AS description','hit','file_path','url_external','slug','created_at','status','updated_at');
        $query->where('status','=','publish');
        // if($params['exhibition_join'] !=0){
        //     $query->whereIn('category_id',[$params['category_id'],$params['exhibition_join']]);
        // }else{
        //     $query->where('category_id','=',$params['category_id']);
        // }
        $query->where('category_id','=',$params['category_id']);
        $query->where('end_date','<=',$date);
        //$query->where('featured','=',"1");
        $query->orderByRaw('created_at DESC');
        $data = $query->paginate(6);
        //$result['items']= $data;
        return $data;

    }


    public function scopeDetail($query,$params){

        $query->select('id','title','description','short_description','created_at','status','meta_title','meta_keywords','meta_description','category_id','start_date','end_date','hit');
        $query->where('slug','=',$params['slug']);

        return $query->first();
    }

    public function scopeDetailId($query,$params){

        $query->select('id','title','description','short_description','created_at','status','meta_title','meta_keywords','meta_description','category_id','start_date','end_date','hit');
        $query->where('id','=',$params['id']);

        return $query->first();
    }


    public function scopeTitle($query,$params){

        $query->select('id','title');
        $query->where('id','=',$params['id']);

        return $query->first();
    }



    public function scopeFrontListRelated($query,$params){

        $query->select('id','title','short_description AS description','hit','file_path','url_external','slug','created_at','status','updated_at');
        $query->where('status','=','publish');
        $query->where('category_id','=',$params['category_id']);
        $query->where('id','!=',$params['related_id']);   
        $query->orderByRaw('created_at DESC');
        $query->limit(10);
        $data = $query->get();
        //$result['items']= $data;
        return $data;
        
    }

    public function scopeFrontListMostView($query,$params){

        $query->select('id','title','short_description AS description','hit','file_path','url_external','slug','created_at','status','updated_at');
        $query->where('status','=','publish');
        $query->where('category_id','=',$params['category_id']);   
        $query->orderByRaw('hit DESC');
        $query->limit(10);
        $data = $query->get();
        //$result['items']= $data;
        return $data;

    }

    public function scopeFrontListRecommend($query,$params){

        $query->select('id','title','short_description AS description','hit','file_path','url_external','slug','created_at','status','updated_at');
        $query->where('status','=','publish');
        $query->where('category_id','=',$params['category_id']);
        $query->where('recommend','=','2');
        $query->where('id','!=',$params['related_id']);  
        $query->orderByRaw('created_at DESC');
        $query->limit(10);
        $data = $query->get();
        //$result['items']= $data;
        return $data;
        
    }


    public function setHashidAttribute($id){
        return $this->attributes['hashid'] = \Hashids::encode($id);
    }


    public function createdBy()
    {
        return $this->belongsTo('App\User','created_by','id')->select('name','id');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User','updated_by','id')->select('name','id');
    }


    public function ExhibitionMaster()
    {
        return $this->belongsTo('App\Modules\Exhibition\Models\ExhibitionMaster','category_id','id')->select('title','id');
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
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }


}
