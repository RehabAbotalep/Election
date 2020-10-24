<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    protected $fillable = [ 'device_id', 'token', 'user_id', 'ios' ];


    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
