<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [ 'name', 'color', 'added_by'];

    public function users()
    {
    	return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function electors()
    {
        return $this->belongsToMany(Elector::class,'elector_group')->withTimestamps();
    }

    public function addedBy()
    {
    	return $this->belongsTo(User::class,'added_by');
    }

    public function getCreatedAtAttribute($value)
    {
    	return Carbon::parse($value)->format('Y-m-d');
    }

    //Search Scope
    public function scopeSearch($query,$searchTerm)
    {
        return $query->where('name' ,'like', '%' . $searchTerm . '%');
    }
}
