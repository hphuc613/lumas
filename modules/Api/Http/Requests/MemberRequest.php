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
        if ($segment === 'update-avatar') {
            return [
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ];
        }

        if ($segment === "profile-update") {
            return [
                'username'          => [
                    'required',
                    'regex:/(^([a-zA-Z0-9_.]+)(\d+)?$)/u',
                    'validate_unique:members, ' . $auth_id
                ],
                'phone'             => 'required|size:8|validate_unique:members, ' . $auth_id,
                'email'             => 'nullable|email|validate_unique:members, ' . $auth_id,
                'password'          => 'min:6|required|regex:/^.*(?=.{2,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/',
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
            'email'             => 'nullable|email|validate_unique:members',
            'password'          => 'min:6|required|regex:/^.*(?=.{2,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/',
            'password_re_enter' => 're_enter_password|required_with:password'
        ];

    }

    public function messages(){
        return [
            'username.required'        => trans('The Username can not be empty.'),
            'username.validate_unique' => trans('The Username was exist.'),
            'username.regex'           => trans('The Username contains invalid characters.'),
            'phone.required'           => trans('The Phone can not be empty.'),
            'phone.validate_unique'    => trans('The Phone was exist.'),
            'phone.regex'              => trans('The Phone contains invalid characters.'),
            'phone.size'               => trans('The Phone must be 8 characters.'),
            'email.validate_unique'    => trans('The Email was exist.'),
            'email'                    => trans('The Email must be a email.'),
            'email.required'           => trans('The Email can not be empty.'),
            'password.required'        => trans('The Password can not be empty.'),
            'password.regex'           => trans('The Password must at least 1 number or 1 english letter'),
            'min'                      => trans('The Password too short.'),
            're_enter_password'        => trans('The Wrong password'),
            'required_with'            => trans('The Re-enter Password can not be empty.'),
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
