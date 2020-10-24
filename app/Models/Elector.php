<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Elector extends Model
{
    /*protected $fillable = [
    	'first_name' , 'gender' ,'constituency_id' ,'area_id' ,'registeration_number', 
        'registeration_date', 'unified_number', 'birth_date' , 'job_id' ,'table_id' , 'notes'
                       ];*/
    protected $guarded = ['id'];

    //protected $dates = ['registeration_date' , 'birth_date'];

    public function constituency()
    {
    	return $this->belongsTo(Constituency::class);
    }

    public function area()
    {
    	return $this->belongsTo(Area::class);
    }

    public function job()
    {
    	return $this->belongsTo(Job::class);
    }
    public function getNameAttribute()
    {
    	return $this->first_name . ' '.$this->second_name. ' '.$this->third_name. ' '.$this->fourth_name. ' '.$this->fifth_name. ' '.$this->sixth_name;
    }

    public function groups()
    {
    	return $this->belongsToMany(Elector::class,'elector_group')->withTimestamps();
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function scopeFullName($query,$name) {
        // Concat the name columns and then apply search query on full name
        $query->where(DB::raw(
            // REPLACE will remove the double white space with single (As defined)
            "REPLACE(
                /* CONCAT will concat the columns with defined separator */
                CONCAT(
                    /* COALESCE operator will handle NUll values as defined value. */
                    COALESCE(first_name,''),' ',
                    COALESCE(second_name,''),' ',
                    COALESCE(third_name,''),' ',
                    COALESCE(fourth_name,''),' ',
                    COALESCE(fifth_name,'')
                ),
            '  ',' ')"
        ),
        'like', '%' . $name . '%');
    }
}
