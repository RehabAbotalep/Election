<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [ 'user_id', 'title', 'body','sender_id'];

    protected $hidden = ['updated_at'];


    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($value)
    {
    	return Carbon::parse($value)->format('Y-m-d');
    }
}
