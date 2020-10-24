<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddDiwaniyahRequest;
use App\Http\Resources\DiwaniyahResource;
use App\Http\Traits\ApiResponse;
use App\Models\Diwaniyah;
use Illuminate\Http\Request;

class DiwaniyahController extends Controller
{   
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $diwaniyahs = Diwaniyah::orderBy('created_at','DESC')->get();
        return $this->dataResponse(DiwaniyahResource::collection($diwaniyahs),null,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddDiwaniyahRequest $request)
    {
        $data = $request->only('owner','occasion','person','date','region','address');

        Diwaniyah::create($data);
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
        $diwaniyah = Diwaniyah::findOrFail($id);
        return $this->dataResponse(new DiwaniyahResource($diwaniyah),null,200);
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
        $data = $request->only('owner','occasion','person','date','region','address');
        $diwaniyah = Diwaniyah::findOrFail($id);

        $diwaniyah->update($data);
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
        $diwaniyah = Diwaniyah::findOrFail($id)->delete();
        return $this->dataResponse(null,trans('all.deleted'),200);
    }
}
