<?php  

namespace App\Http\Transformers;


use App\Models\Notification;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{

    //protected $availableIncludes = ['roles'];


    public function __construct($paramter)
    {
        $this->paramter = $paramter;
    }

    public function transform(Notification $notification)
    {
        $array = [

            'title'     => $notification->title,
            'body'      => $notification->body,
            'by_me'     => ($notification->sender_id == $this->paramter) ? 1 : 0,
        ];


        return $array;
    }


}




?>