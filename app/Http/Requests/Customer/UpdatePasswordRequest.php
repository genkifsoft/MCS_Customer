<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\AbstractApiRequest;

class UpdatePasswordRequest extends AbstractApiRequest
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
            'current_pass'            => 'required|max:80|min:8',
            'password'                => 'required|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/|max:64|min:8|confirmed',
            'password_confirmation'   => 'required',
        ];
    }

    public function messages()
    {
        return [
            'current_pass.required'                    => 'UPDATE_PASSWORD_REQUIRED_428',
            'current_pass.max'                         => 'UPDATE_PASSWORD_MAX',
            'current_pass.min'                         => 'UPDATE_PASSWORD_MIN',
            'password.required'                        => 'UPDATE_NEW_PASSWORD_REQUIRED_428',
            'password.max'                             => 'UPDATE_NEW_PASSWORD_MAX',
            'password.min'                             => 'UPDATE_NEW_PASSWORD_MIN',
            'password.confirmed'                       => 'UPDATE_NEW_PASSWORD_COMFIRMED',
            'password_confirmation.required'           => 'UPDATE_COMFIRM_PASSWORD_REQUIRED_428',
            'password_confirmation.max'                => 'UPDATE_COMFIRM_PASSWORD_MAX',
            'password_confirmation.min'                => 'UPDATE_COMFIRM_PASSWORD_MIN',
            'password_confirmation.confirmed'          => 'UPDATE_COMFIRM_PASSWORD_COMFIRMED_428',
        ];
    }
}
