<?php

namespace App\Modules\Documentsdownload\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class DocumentsFranchise extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    protected $table = 'documents_download';
    protected $primaryKey = 'id';


    protected $fillable = [
        'title',
        'file_name',
        'file_type',
        'file_path',
        'document_type',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'franchise_id'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    public function scopeData($query,$params)
    {   
        $query->select('id','title','file_name','file_path','file_type','created_at','created_by','updated_by','status');
        return $query->where('franchise_id',$params['franchise_id'])
                    ->with('createdBy','updatedBy')
                    ->orderBy('created_at','desc')
                    ->get();
    }

    public function scopeDataLink($query,$params)
    {
        $query->select('id','title_th AS title','file_name_th','file_name_en','file_path_th','file_path_en');
        return $query->whereIn('status',$params['status'])->where('document_type','=',$params['document_type'])->orderBy('id','desc')->first();
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
    }

}
