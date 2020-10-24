<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Hash;

class User extends Authenticatable
{
    use Notifiable , HasApiTokens , HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'password', 'user_name', 'account_type_id' ,'guarantee_percentage' ,'comitee_id'
    ];

    protected $guard_name = 'api';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }

    public function addedGroups()
    {
        return $this->hasMany(Group::class,'added_by');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function type()
    {
        return $this->belongsTo(AccountType::class,'account_type_id');
    }

    public function committee()
    {
        return $this->belongsTo(Commitee::class,'comitee_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class,'user_id');
    }
}
