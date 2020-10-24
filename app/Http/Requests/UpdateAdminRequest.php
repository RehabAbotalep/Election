<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
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
            'name' => 'sometimes|nullable|string',
            'user_name' => 'sometimes|nullable|unique:users,user_name,'. $user->id,
            'phone'     => 'sometimes|nullable|unique:users,phone,'. $user->id,
            'password'  => 'sometimes|nullable',
            'account_type_id' => 'sometimes|nullable|exists:account_types,id',
            'guarantee_percentage' => 'sometimes|nullable|numeric|between:0,100',
            'comitee_id' => 'sometimes|nullable|exists:commitees,id'
        ];
    }
}
