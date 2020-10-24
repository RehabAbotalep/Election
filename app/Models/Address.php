<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
   	protected $fillable = ['part','street','gadah','home','qasima'];
   	
   	public function elector()
   	{
   		return $this->belongsTo(Elector::class);
   	}
}
