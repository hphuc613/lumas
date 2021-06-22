<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpValidation extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'          => [
                'required',
                'regex:/(^([a-zA-Z0-9_.]+)(\d+)?$)/u',
                'validate_unique:members'
            ],
            'email'             => 'required|email|validate_unique:members',
            'password'          => 'min:6|required',
            'password_re_enter' => 're_enter_password|required_with:password'
        ];

    }

    public function messages()
    {
        return [
            'required'          => ':attribute' . trans(' can not be empty.'),
            'regex'             => ':attribute' . trans(' contains invalid characters.'),
            'email'             => ':attribute' . trans(' must be the email.'),
            'min'               => ':attribute' . trans('  too short.'),
            're_enter_password' => trans('Wrong password'),
            'required_with'     => ':attribute' . trans(' can not be empty.'),
            'validate_unique'   => ':attribute' . trans(' was exist.'),
        ];
    }

    public function attributes()
    {
        return [
            'username'          => trans('Username'),
            'email'             => trans('Email'),
            'password'          => trans('Password'),
            'password_re_enter' => trans('Re-enter Password'),
            'status'            => trans('Re-enter Password'),
            'role_id'           => trans('Role')
        ];
    }
}
