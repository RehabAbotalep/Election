<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddElectorRequest extends FormRequest
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
            'electors'    => 'required|array',
            'electors.*'  => 'distinct|exists:electors,id',
            'group'       => 'required|exists:electors,id',
        ];
    }
}
