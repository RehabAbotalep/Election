<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
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
            'name' => 'required|string',
            'user_name' => 'required|unique:users',
            'phone'     => 'required|unique:users',
            'password'  => 'required',
            'account_type_id' => 'required|exists:account_types,id',
            'guarantee_percentage' => 'required|numeric|between:0,100',
            'comitee_id' => 'required|exists:commitees,id'
        ];
    }
}
