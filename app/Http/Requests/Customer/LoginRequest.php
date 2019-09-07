<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\AbstractApiRequest;

class LoginRequest extends AbstractApiRequest
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
            'email'       => 'required|max:80|min:8',
            'password'    => 'required|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/|max:64|min:8',
        ];
    }

    public function messages()
    {
        return [
            'email.max'              => 'Email độ dài quá tối đa 80 ký tự',
            'email.min'              => 'Email độ dài không được nhỏ hơn 8 ký tự',
            'email.required'         => 'Email không được để trống',
            'password.max'           => 'Password không được lớn hơn 64 ký tự',
            'password.min'           => 'Password không được nhỏ hơn 8 ký tự',
            'password.required'      => 'Password không được để trống',
            'password.regex'         => 'Mật khẩu phải bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt',
        ];
    }
}
