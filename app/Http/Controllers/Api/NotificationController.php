<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignNotificationRequest;
use App\Http\Resources\UserNotificationResource;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use App\Http\Traits\PushNotificationTrait;
use App\Http\Transformers\NotificationTransformer;
use App\Http\Transformers\UserTransformer;
use App\Models\DeviceToken;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class NotificationController extends Controller
{	
	use ApiResponse , PushNotificationTrait;

    //submit device token for sending general notification(Taakad App)
    public function submitToken(Request $request){

        $request->validate([
            'token'     => 'required',
            'device_id' => 'required'
        ]);

        $token = DeviceToken::updateOrCreate(
           ['device_id' => $request->device_id],
           ['ios' => $request->ios, 'user_id' => auth('api')->id(),'token' => $request->token]
        );

        return $this->dataResponse(null,trans('all.added'),200);
        
    }

    //get Users Notification (pagination)
    public function notifications(Request $request){
        $skip  = ($request->start) ?? 0;
        $id = auth('api')->id();

        $notifications = Notification::where('user_id',$id)
                                    ->orWhere('sender_id',$id)
                                    ->orderBy('created_at', 'DESC')
                                    ->take(10)->skip($skip)->get();

        $count = $notifications->count();
        $data = fractal()
                  ->collection($notifications)
                  ->transformWith(new NotificationTransformer($id))
                  ->serializeWith(new \Spatie\Fractalistic\ArraySerializer())
                  ->toArray();

        return $this->paginationResponse($data,$count);
        

        return $this->dataResponse($notifications,null,200);
    }

    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        
        $input = $request->only('title','body');
        $fcmData = array(
            'title' => $input['title'],
            'body' => $input['body'],
            'type' => 'General',
        );
        $ids = explode (",", $request->ids);
        
       // $tokens = DeviceToken::whereIn('user_id', $ids)->whereNotNull('token');

        $ios_tokens = DeviceToken::whereIn('user_id', $ids)->whereNotNull('token')->whereIos(1)->pluck('token')->all();

        $android_tokens = DeviceToken::whereIn('user_id', $ids)->whereNotNull('token')->whereIos(0)->pluck('token')->all();

        $android_user_ids = DeviceToken::whereNotNull('token')->whereIos(0)->pluck('user_id')->all();

        $ios_user_ids = DeviceToken::whereNotNull('token')->whereIos(1)->pluck('user_id')->all();

        $result = [];
        if (!empty($android_tokens)) {
            $result['android'] = \PushNotification::setService('fcm')
                ->setMessage([
                    'notification' => [
                        'title' => $fcmData['title'],
                        'body' => $fcmData['body'],
                        'sound' => 'default',
                        'type' => 'General',
                    ],
                    'data' => $fcmData,
                ])
                ->setDevicesToken($android_tokens)
                ->send()
                ->getFeedback();
            $this->StoreNotification($android_user_ids, $fcmData,auth('api')->id());
        }

        //* IOS
        if (!empty($ios_tokens)) {
            $result['ios'] = \PushNotification::setService('fcm')
                ->setMessage([
                    'notification' => [
                        'title' => $input['title'],
                        'body' => $input['body'],
                        'sound' => 'default',

                    ],
                    'data' => $fcmData,
                ])
                ->setDevicesToken($ios_tokens)
                ->send()
                ->getFeedback();
            $this->StoreNotification($ios_user_ids, $fcmData,auth('api')->id());
        }
        return response()->json($result);
        //return $this->dataResponse(null,'Send Successfully',200);
    }

    //get list of users that i have chated with
    public function users()
    {   
        $id = auth('api')->id();

        $notifications = Notification::where('user_id',$id)
                                    ->orWhere('sender_id',$id);

        $senders   = $notifications->pluck('sender_id')->toArray();
        $recievers = $notifications->pluck('user_id')->toArray();
        $array = array_unique(array_merge($senders,$recievers));

        $users = User::whereIn('id',$array)->where('id','!=',$id)->get();

        $data  = fractal()
                ->collection($users)
                ->transformWith(new UserTransformer())
                ->serializeWith(new \Spatie\Fractalistic\ArraySerializer())
                ->toArray();

        return $this->dataResponse($data,null,200);
        
    }

    //get chat notification between login user and other one

    public function userNotification($id,Request $request)
    {
        $loginUser = auth('api')->id();
        $skip  = ($request->start) ?? 0;

        $notifications = Notification::where('sender_id',$id)
                                    ->where('user_id',$loginUser)
                                    ->orWhere('user_id',$id)
                                    ->where('sender_id',$loginUser)
                                    ->orderBy('created_at', 'DESC')
                                    ->take(10)->skip($skip)->get();

        $count = $notifications->count();
        $data = fractal()
                  ->collection($notifications)
                  ->transformWith(new NotificationTransformer($loginUser))
                  ->serializeWith(new \Spatie\Fractalistic\ArraySerializer())
                  ->toArray();

        return $this->paginationResponse($data,$count);
    }
}
