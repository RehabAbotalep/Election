<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddElectorRequest;
use App\Http\Traits\ApiResponse;
use App\Http\Transformers\ElectorTransformer;
use App\Models\Elector;
use App\Models\Group;
use Illuminate\Http\Request;

class ElectorController extends Controller
{	
	use ApiResponse;

    public function electors(Request $request)
    {	
    	$skip = ($request->start) ?? 0;

    	$electors = Elector::orderBy('id','ASC');
    	

    	/*$electors = $electors->when($request->first_name,fn($q) => $q->where('first_name',$request->first_name)
    				->when($request->second_name,fn($q) => $q->where('second_name ',$request->second_name))
    				->when($request->third_name,fn($q) => $q->where('third_name ',$request->third_name))
    				->when($request->fourth_name,fn($q) => $q->where('fourth_name',$request->fourth_name))
    				->when($request->fifth_name,fn($q) => $q->where('fifth_name',$request->fifth_name))
                    ->when($request->constituency,fn($q) => $q->where('constituency_id',$request->constituency))
                    ->when($request->area,fn($q) => $q->where('area_id',$request->area))
                    ->when($request->job,fn($q) => $q->where('job_id',$request->job))
                    ->when($request->gender,fn($q) => $q->where('gender',$request->gender))
                    ->when($request->status,fn($q) => $q->where('notes',$request->status))
                    ->when($request->age_from,fn($q) => $q->whereBetween('age', [$request->age_from, $request->age_to]))
                    ->when($request->excluded,fn($q) => $q->where('name','!=',$request->excluded)))    
               		->take(10)->skip($skip);
        return $electors->get();*/

        if( !empty($request->first_name) )
        {
        	$electors = $electors->where('first_name','LIKE',"%{$request->first_name}%");
        }
        if( !empty($request->second_name) )
        {
        	$electors = $electors->where('second_name','LIKE',"%{$request->second_name}%");
        }
        if( !empty($request->third_name) )
        {
        	$electors = $electors->where('third_name','LIKE',"%{$request->third_name}%");
        }
        if( !empty($request->fourth_name) )
        {
        	$electors = $electors->where('fourth_name','LIKE',"%{$request->fourth_name}%");
        }
        if( !empty($request->fifth_name) )
        {
        	$electors = $electors->where('fifth_name','LIKE',"%{$request->fifth_name}%");
        }
        if( !empty($request->full_name) )
        {
            $electors = $electors->fullName($request->full_name);
        }
        if( !empty($request->constituency) )
        {
        	$electors = $electors->where('constituency_id',$request->constituency);
        }
        if( !empty($request->registeration_number) )
        {
            $electors = $electors->where('registeration_number',$request->registeration_number);
        }
        
        if( !empty($request->area) )
        {
        	$electors = $electors->where('area_id',$request->area);
        }
        if( !empty($request->job) )
        {
        	$electors = $electors->where('job_id',$request->job);
        }
        if( !empty($request->gender))
        {
        	$electors = $electors->where('gender',$request->gender);
        }
        if( !empty($request->unified_number))
        {
            $electors = $electors->where('unified_number',$request->unified_number);
        }
        if( !empty($request->status) )
        {
        	$electors = $electors->where('notes',$request->status);
        }
        if( !empty($request->age_from) )
        {
        	$electors = $electors->whereBetween('age', [$request->age_from, $request->age_to]);
        }
        if( !empty($request->excluded) )
        {   $excluded = explode (",", $request->excluded);
        	$electors = $electors->whereNotIn('first_name',$excluded);
        }

        if( !empty($request->commitee) )
        {
        	$electors = $electors->where('commitee_id',$request->commitee)->whereVoted(0);
        }

        if( !empty($request->address_key))
        {
            $electors = $electors->whereHas('address',function($q) use($request){
                $q->where($request->address_key,$request->value);
            });
        }
        $count = $electors->count();

        $electors =  $electors->take(500)->skip($skip)->get();
    	$data = fractal()
                  ->collection($electors)
                  ->transformWith(new ElectorTransformer())
                  ->toArray();
        return $this->paginationResponse($data,$count);
    }

    public function electorDetails($id)
    {
    	$elector = Elector::findOrFail($id);
    	return  fractal()
                  ->item($elector)
                  ->transformWith(new ElectorTransformer('profile'))
                  ->toArray();
    }

    public function addToGroup(AddElectorRequest $request)
    {
    	$group = Group::findOrFail($request->group);
    	$group->electors()->attach($request->electors);
    	return $this->dataResponse(null,trans('all.added'),200);
    }

    public function updateElector($id,Request $request)
    {
    	$elector = Elector::findOrFail($id);
    	$elector->update([
            "phone" => $request->phone,
            "guarantee_percentage" => $request->guarantee_percentage,
        ]);
    	return $this->dataResponse(null,trans('all.updated'),200);
    }
}
