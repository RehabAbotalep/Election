<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diwaniyah extends Model
{
    protected $fillable = ['owner', 'occasion','region','address','date','person'];
    
}
