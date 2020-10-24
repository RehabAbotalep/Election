<?php  

namespace App\Http\Transformers;


use App\Http\Resources\GroupResource;
use App\Http\Resources\PredefineDataResource;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{


    public function __construct($paramter = false)
    {
        $this->paramter = $paramter;
    }

    public function transform(User $user)
    {
        $array = [

            'id'        => $user->id,
            'name'      => $user->name,
            'user_name' => $user->user_name,
            'phone'     => $user->phone,
            //'registeration_number'    => $elector->registeration_number,
        ];

        if( $this->paramter == 'profile')
        {
            $array['percentage'] = $user->guarantee_percentage;

            $array['type'] = new PredefineDataResource($user->type);
            $array['committee']    = new PredefineDataResource($user->committee);
            //'permissions'  = $user->getPermissionNames()
            $array['permissions']  = PredefineDataResource::collection($user->permissions);
            $array['added_groups'] = GroupResource::collection($user->addedGroups);
            $array['groups']       = GroupResource::collection($user->groups);
        }

        return $array;
    }


}




?>