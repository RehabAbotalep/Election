<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddGroupRequest;
use App\Http\Resources\GroupResource;
use App\Http\Traits\ApiResponse;
use App\Http\Transformers\ElectorTransformer;
use App\Models\Elector;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{	
	use ApiResponse;
    
    //add new Group
    public function add(AddGroupRequest $request)
    {
    	$group = Group::create([
    		'name'     => $request->name,
    		'color'    => $request->color,
    		'added_by' => auth('api')->id(),


    	]);
    	$group->users()->attach($request->members);

    	return $this->dataResponse(null,trans('all.group_created'),200);
    }

    public function groups(Request $request)
    {   
        $groups = Group::orderBy('created_at','DESC');

        //$skip = ($request->start) ?? 0;

        if( !empty($request->q))
        {   
            
            $groups = $groups->search($request->q);
        }

        return $this->dataResponse(GroupResource::collection($groups->get()),null,200);

    }

    //List of elector in group
    public function electors($id,Request $request)
    {
        $group = Group::findOrFail($id);

        $skip = ($request->start) ?? 0;

        $electors = $group->electors()->take(10)->skip($skip);
        return  fractal()
                  ->collection($electors->get())
                  ->transformWith(new ElectorTransformer())
                  ->toArray();
    }

    //delete Elector from group

    public function deleteElector($group_id,$elector_id)
    {
        $elector = Elector::findOrFail($elector_id);

        $group   = Group::findOrFail($group_id);

        $group->electors()->detach($elector);
        return $this->dataResponse(null,trans('all.deleted'),200);
    }

    //delete Group
    public function destroy($id)
    {
        $group = Group::findOrFail($id)->delete();
        return $this->dataResponse(null,trans('all.deleted'),200);
    }

    //show Group
    public function show($id)
    {
        $group = Group::findOrFail($id);
        return $this->dataResponse(new GroupResource($group),null,200);
    }

    public function update(Request $request,$id)
    {
        $group = Group::findOrFail($id);

        $data = $request->only('name','color');
        $data['added_by'] = auth('api')->id();

        $group->update($data);

        $group->users()->sync($request->members);

        return $this->dataResponse(null,trans('all.updated'),200);
    }
}
