<?php  

namespace App\Http\Transformers;

use App\Models\Elector;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class ElectorTransformer extends TransformerAbstract
{


    public function __construct($paramter = false)
    {
        $this->paramter = $paramter;
    }

    public function transform(Elector $elector)
    {
        $array = [

            'id'        => $elector->id,
            'name'      => $elector->name,
            'phone'     => $elector->phone,
            //'registeration_number'    => $elector->registeration_number,
        ];

        if( $this->paramter == 'profile')
        {
            $array['constituency'] = $elector->constituency->name;
            $array['job'] = $elector->job->name;
            $array['gender'] = $elector->gender == 1 ? 'ذكر' : 'أنثى';
            $array['area'] = $elector->area->name;
            $array['registeration_number'] = $elector->registeration_number;
            $array['registeration_date'] = $elector->registeration_date;
            $array['unified_number'] = $elector->unified_number;
            $array['birth_date'] = $elector->birth_date;
            $array['age'] = $elector->age;
            $array['notes'] = $elector->notes;
            $array['address'] = $elector->address;
            $array['guarantee_percentage'] = $elector->guarantee_percentage;
        }

        return $array;
    }


}




?>