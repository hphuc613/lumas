<?php

namespace Modules\Api\Http\Requests;

use App\AppHelpers\Helper;

class MemberRequest extends ApiRequest{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        $segment = Helper::segment(2);
        $auth_id = auth('api-member')->id();
        if($segment === "profile-update"){
            return [
                'username'          => [
                    'required',
                    'regex:/(^([a-zA-Z0-9_.]+)(\d+)?$)/u',
                    'validate_unique:members, ' . $auth_id
                ],
                'phone'             => 'required|size:8|validate_unique:members, ' . $auth_id,
                'email'             => 'required|email|validate_unique:members, ' . $auth_id,
                'password'          => 'min:6|nullable',
                'password_re_enter' => 're_enter_password|required_with:password'
            ];
        }

        return [
            'username'          => [
                'required',
                'regex:/(^([a-zA-Z0-9_.]+)(\d+)?$)/u',
                'validate_unique:members'
            ],
            'phone'             => 'required|size:8|validate_unique:members',
            'email'             => 'required|email|validate_unique:members',
            'password'          => 'min:6|required',
            'password_re_enter' => 're_enter_password|required_with:password'
        ];

    }

    public function messages(){
        return [
            'username.required'        => trans('Username can not be empty.'),
            'username.validate_unique' => trans('Username was exist.'),
            'username.regex'           => trans('Username contains invalid characters.'),
            'phone.required'           => trans('Phone can not be empty.'),
            'phone.validate_unique'    => trans('Phone was exist.'),
            'phone.regex'              => trans('Phone contains invalid characters.'),
            'email.validate_unique'    => trans('Email was exist.'),
            'email'                    => trans('Email must be a email.'),
            'email.required'           => trans('Email can not be empty.'),
            'password.required'        => trans('Password can not be empty.'),
            'min'                      => trans('Password too short.'),
            're_enter_password'        => trans('Wrong password'),
            'required_with'            => trans('Re-enter Password can not be empty.'),
        ];
    }

    public function attributes(){
        return [
            'username'          => trans('Username'),
            'email'             => trans('Email'),
            'password'          => trans('Password'),
            'password_re_enter' => trans('Re-enter Password'),
            'status'            => trans('Re-enter Password'),
            'role_id'           => trans('Role'),
            'phone'             => trans('Phone')
        ];
    }
}
