<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PredefineDataResource;
use App\Http\Traits\ApiResponse;
use App\Models\AccountType;
use App\Models\Area;
use App\Models\Constituency;
use App\Models\Job;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PredefineDataController extends Controller
{	
	use ApiResponse;

    //get All areas
    public function areas(Request $request)
    {	
    	$skip  = ($request->start) ?? 0;
        
        $areas = Area::take(10)->skip($skip);

    	return $this->dataResponse(PredefineDataResource::collection($areas->get()),null,200);
    }

    public function jobs(Request $request)
    {	
    	$skip = ($request->start) ?? 0;
        
        $jobs = Job::take(10)->skip($skip);

    	return $this->dataResponse(PredefineDataResource::collection($jobs->get()),null,200);
    }

    public function constituencies(Request $request)
    {	
    	$skip = ($request->start) ?? 0;
        
        $constituencies = Constituency::take(10)->skip($skip);

    	return $this->dataResponse(PredefineDataResource::collection($constituencies->get()),null,200);
    }

    public function accountTypes()
    {
    	$types = AccountType::all();
    	return $this->dataResponse(PredefineDataResource::collection($types),null,200);
    }

    public function permissions()
    {
    	$types = Permission::all();
    	return $this->dataResponse(PredefineDataResource::collection($types),null,200);
    }
}
