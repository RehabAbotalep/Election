<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommiteeResource;
use App\Http\Resources\PredefineDataResource;
use App\Http\Traits\ApiResponse;
use App\Http\Transformers\ElectorTransformer;
use App\Models\Commitee;
use App\Models\Elector;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    use ApiResponse;

    //get All committees
    public function committees(Request $request)
    {
    	$committees = Commitee::orderBy('created_at','DESC');

        //$skip = ($request->start) ?? 0;

        if( !empty($request->q))
        {
            $committees = $committees->search($request->q);
        }

    	return $this->dataResponse(CommiteeResource::collection($committees->get()),null,200);

    }

    //get Unvoted Electros in commitee
    public function electors($id,Request $request)
    {	
    	$skip = ($request->start) ?? 0;

    	$commitee = Commitee::findOrFail($id);
    	$electors = $commitee->electors()->whereVoted($request->voted);

    	$count = $electors->count();
    	$electors = $electors->take(500)->skip($skip)->get();

    	$electors = fractal()
                        ->collection($electors)
                        ->transformWith(new ElectorTransformer())
                        ->serializeWith(new \Spatie\Fractalistic\ArraySerializer())
                        ->toArray();
        return $this->paginationResponse($electors,$count);

    }

    //vote
    public function vote($id)
    {
    	$elector = Elector::findOrFail($id);
    	$elector->update(["voted" => 1]);

    	return $this->dataResponse(null,trans('all.voted'),200);
    }

    public function update($id,Request $request)
    {
        $committee = Commitee::findOrFail($id);
        $committee->update($request->only('name','screening_ratio'));
        return $this->dataResponse(null,trans('all.updated'),200);
    }
}
