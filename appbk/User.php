<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Hash;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

/**
 * Class User
 *
 * @package App
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
*/
class User extends Authenticatable implements HasMediaConversions
{
    use Notifiable, HasRolesAndAbilities, HasMediaTrait;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'remember_token', 'status','phone','activate','username','activate_token','forgotpassword_token','date_of_birth','facebook_id'];

    public function registerMediaConversions()
    {
        $this
            ->addMediaConversion('thumb150x150')
            ->width(150)
            ->height(150)
            ->performOnCollections('avatar');
    }


    public function scopeData($query,$params){

        $query->select('id','username','email','activate','status','activate_token');
        if($params['retrieving_results'] =='get'){
            return $query->whereIn('activate',$params['activate'])
            //->toSql();
            ->get();
        }else{
            return $query->whereIn('activate',$params['activate'])
            //->toSql();
            ->first();
        }

    }
}
