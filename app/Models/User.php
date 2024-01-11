<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'login_type',
        'name',
        'email',
        'phone',
        'password',
        'is_verified',
        'otp',
        'address',
        'lat',
        'long',
        'age',
        'gender',
        'state_id',
        'city_id',
        'profession',
        'imageurl',
        'blood_group',
        'is_interested',
        'type_of_ngo',
        'type_of_blood_bank',
        'blood_bank_history',
        'is_disable',
        'is_approved'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function UserAuthMaster() {
        return $this->hasMany('App\Models\UserAuthMaster', 'user_id', 'id');
    }
    public function ngos() {
        return $this->hasone('App\Models\Ngo', 'user_id', 'id');
    }
    public function members() {
        return $this->belongsToMany('App\Models\MemberDetails', 'user_id', 'id');
    }
    
}
