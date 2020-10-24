<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use App\Models\Commitee;
use App\Models\Elector;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::role('admin')->get();
        return $this->dataResponse(UserResource::collection($users),null,200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdminRequest $request)
    {   
        $data = $request->only('name','user_name','password','phone','account_type_id','guarantee_percentage','comitee_id');

        $user= User::create($data);
        $user->assignRole('admin');
        $user->givePermissionTo($request->permissions);
        $user->groups()->attach($request->groups);
        return $this->dataResponse(null,trans('all.added'),200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return $this->dataResponse(new UserResource($user),null,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only('name','user_name','password','phone','account_type_id','guarantee_percentage','comitee_id');

        $user = User::findOrFail($id);

        $request->validate([
            'user_name' => 'sometimes|nullable|unique:users,user_name,'. $user->id,
            'phone'     => 'sometimes|nullable|unique:users,phone,'. $user->id,
            'password'  => 'sometimes|nullable',
            'account_type_id' => 'sometimes|nullable|exists:account_types,id',
            'guarantee_percentage' => 'sometimes|nullable|numeric|between:0,100',
            'comitee_id' => 'sometimes|nullable|exists:commitees,id'
        ]);

        $user->update($data);

        if( !empty($request->permissions) ){
            $user->syncPermissions($request->permissions);
        }

        if( !empty($request->groups) ){
            $user->groups()->sync($request->groups);
        }
        
        return $this->dataResponse(null,trans('all.updated'),200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id)->delete();
        return $this->dataResponse(null,trans('all.deleted'),200);
    }

    //statistics
    public function statistics()
    {
        $array['group_count'] = Group::count();
        
        $array['admins'] = User::role('admin')->count();
        $array['voted_in_group'] = Elector::whereHas('groups',function($q){
            $q->where('voted',1);
        })->count();

        $array['voted_in_commitee'] = Elector::whereDoesntHave('groups',function($q){
            $q->where('voted',1);
        })->count();

        $array['electors_in_group'] = Elector::has('groups')->count();

        $array['max_voted_commitee'] = $this->votedCommitee('DESC');

        $array['min_voted_commitee'] = $this->votedCommitee('ASC');

        $array['max_electors_group'] = $this->electorGroup('DESC');

        $array['min_electors_group'] = $this->electorGroup('ASC');
        
        return $array;
    }

    private function votedCommitee($orderBy)
    {
        $commitee = Commitee::whereHas('electors',function($q){
            $q->where('voted',1);
        })->withCount('electors')->orderBy('electors_count',$orderBy)->first();
        $array['name'] = $commitee->name;
        $array['count'] = $commitee->electors_count;
        return $array;
    }

    private function electorGroup($orderBy)
    {
        $group = Group::has('electors')->withCount('electors')
                        ->orderBy('electors_count',$orderBy)->first();

        $array['name'] = $group->name;
        $array['count'] = $group->electors_count;
        return $array;
    }

    
}
