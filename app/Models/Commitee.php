<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commitee extends Model
{
    protected $fillable = [ 'name', 'screening_ratio'];
    protected $hidden = ['created_at', 'updated_at'];

    public function electors()
    {
    	return $this->hasMany(Elector::class);
    }

    //Search Scope
    public function scopeSearch($query,$searchTerm)
    {
    	return $query->where('name' ,'like', '%' . $searchTerm . '%');
    }
}
