<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddDiwaniyahRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'owner' => 'required|string',
            'occasion' => 'required|string',
            'region'     => 'required|string',
            'address'  => 'required|string',
            'date' => 'required|date_format:Y-m-d',
            'person' => 'required|string',
        ];
    }
}
