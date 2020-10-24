<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use App\Http\Transformers\UserTransformer;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
    	$request->validate([
            'login'    => 'required',
            'password' => 'required'

        ]);
        $user = User::where('phone',$request->login)->orWhere('user_name',$request->login)->first();

      	if( $user ){

	        if(Hash::check($request->password, $user->password)){

	        	Auth::login($user);

	        	$token = $user->createToken('Elections')->accessToken;
                $data  = fractal()
                        ->item($user)
                        ->transformWith(new UserTransformer('profile'))
                        ->serializeWith(new \Spatie\Fractalistic\ArraySerializer())
                        ->toArray();

	          	return $this->fullDataResponse($token,$data,trans('all.login'),200);
	        }
    	}
        return $this->errorResponse(trans('all.unautharized'),null,401);
        /*$user = User::create([
        	'name' => $request->name,
        	'phone' => $request->phone,
        	'user_name' => $request->user_name,
        	'password'  => Hash::make($request->password),
        	'account_type_id' => $request->type
        ]);
        $role = Role::find(1);
        $user->assignRole($role);*/

    }

    public function profile()
    {
    	$user = auth('api')->user();

        $data  = fractal()
                ->item($user)
                ->transformWith(new UserTransformer('profile'))
                ->serializeWith(new \Spatie\Fractalistic\ArraySerializer())
                ->toArray();

    	return $this->dataResponse($data,null,200);
    }


}
