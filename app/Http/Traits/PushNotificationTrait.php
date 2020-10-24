<?php

namespace App\Http\Traits;

use App\DeviceToken;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;



trait PushNotificationTrait
{

    public function StoreNotification($users, $noifiticationData,$sender_id)
    {
        foreach ($users as $key => $value) {
            $data = array(
                'user_id' => $value,
                'body' => $noifiticationData['body'],
                'title' => $noifiticationData['title'],
                'sender_id' => $sender_id,
            );
            $this->storUserNotificaiton($data);
        }
    }

    public function storUserNotificaiton($data)
    {
        
        Notification::create($data);
    }

    public function sendNotification($data)
    {
        $fcmData = [];

        $fcmData = array(
            'title' => $data['title'],
            'body' => $data['body'],
            'type' => $data['type'],
        );

        $result = [];
        if ($data['androidToken'] != null) {
            $result['android'] = \PushNotification::setService('fcm')
                ->setMessage([
                    'notification' => [
                        'title' => $data['title'],
                        'body' => $data['body'],
                        'sound' => 'default',
                        'type' => $data['type'],

                    ],
                    'data' => $fcmData,
                ])
                ->setDevicesToken($data['androidToken'])
                ->send()
                ->getFeedback();
            $this->storUserNotificaiton($data);
        }

        //* IOS
        $fcmData = [
            'title' => $data['title'],
            'body' => $data['body'],
            'type' => $data['type'],
        ];

        if ($data['iosToken'] != null) {
            $result['ios'] = \PushNotification::setService('fcm')
                ->setMessage([
                    'notification' => [
                        'title' => $data['title'],
                        'body' => $data['body'],
                        'type' => $data['type'],
                        'sound' => 'default',
                    ],
                    'data' => $fcmData,
                ])
                ->setDevicesToken($data['iosToken'])
                ->send()
                ->getFeedback();
            $this->storUserNotificaiton($data);
        }

        /*=====  End of For Android  ======*/

        return response()->json($result);
    }


}