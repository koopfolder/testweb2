<?php

namespace App\Modules\Article\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class ThaiHealthWatchFormLogs extends Model implements HasMediaConversions
{
    use HasMediaTrait;
    protected $table = 'thai_health_watch_form_logs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'form_id',
        'json_data',
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
        return \App::getLocale();
    }


    public function scopeData($query,$params)
    {
        $lang = $this->getLang();
        $query->select('id','form_id','json_data','created_at');
        return $query->where('form_id',$params['form_id'])->with('name')
            ->orderBy('created_at','DESC')
            //->toSql();
            ->get();
    }

 
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function createdBy()
    {
        return $this->belongsTo('App\User','created_by','id')->select('name','id');
    }


    public function name()
    {
        return $this->belongsTo('App\Modules\Article\Models\Article','form_id','id')->select('title','id','dol_json_data');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User','updated_by','id')->select('name','id');
    }

    public function frontDocuments()
    {
        $lang = \App::getLocale();
        if($lang =='th'){
        return  $this->hasMany('App\Modules\Documentsdownload\Models\Documents','article_id','id')->select('id','title_th AS title','file_path_th AS file_path','article_id');
        }else{
        return  $this->hasMany('App\Modules\Documentsdownload\Models\Documents','article_id','id')->select('id','title_en AS title','file_path_en AS file_path','article_id');
        }
    }


    public function documents()
    {
        return $this->hasMany('App\Modules\Documentsdownload\Models\Documents','article_id','id')->select('id','title_th','title_en','file_path_th','file_path_en','article_id');
    }


    public function document_reports()
    {
        return $this->hasMany('App\Modules\Documentsdownload\Models\Documents','model_id','id')->select('model_id','download','updated_at');
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
    }

}
