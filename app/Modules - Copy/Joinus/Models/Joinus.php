<?php

namespace App\Modules\Joinus\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Joinus extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    protected $table = 'join_us';
    protected $primaryKey = 'id';


    protected $fillable = [
        'position_id',
        'name',
        'surname',
        'birthdate',
        'sex',
        'status',
        'phone',
        'id_type',
        'id_no',
        'education',
        'educational_institutions',
        'educational_institutions_other',
        'province',
        'prefix',
        'attached_images',
        'attachment_history',
        'other_documents',
        'other_documents_2',
        'other_documents_3',
        'accept_data',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'position_1',
        'company_1',
        'duration_of_service_year_1',
        'duration_of_service_month_1',
        'position_2',
        'company_2',
        'duration_of_service_year_2',
        'duration_of_service_month_2',
        'position_3',
        'company_3',
        'duration_of_service_year_3',
        'duration_of_service_month_3',
        'nationality',
        'email',
        'field_of_study_major'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */


    public function scopeData($query,$params)
    {
        $lang = \App::getLocale();
        // if($lang === 'th'){
        //     $query->select('year','description_th AS description','created_at','created_by','updated_by');
        // }else{
        //     $query->select('year','description_en AS description','created_at','created_by','updated_by');
        // }
            $query->select('*');
        return $query->whereIn('status',$params)->with('createdBy','updatedBy','position')->orderBy('created_at','DESC')
                    ->orderBy('created_at','DESC')
                    //->toSql();
                    ->get();
    }

    public function scopeViewmore($query,$params)
    {

            $query->select('*');
        return $query->where('id',$params['id'])->with('createdBy','updatedBy','position')->first();
    }


    public function createdBy()
    {
        return $this->belongsTo('App\User','created_by','id')->select('name','id');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User','updated_by','id')->select('name','id');
    }


    public function position()
    {
        return $this->belongsTo('App\Modules\Joinus\Models\Careers','position_id','id')->select('position_th','id');
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
