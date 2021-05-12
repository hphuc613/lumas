<?php

namespace Modules\Member\Http\Requests;

use App\AppHelpers\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MemberRequest extends FormRequest{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        $method = Helper::segment(2);
        if(Helper::segment(0) === 'member-profile'){
            return [
                'name'                => 'required',
                'username'            => [
                    'required',
                    'regex:/(^([a-zA-Z0-9!_.]+)(\d+)?$)/u',
                    'validate_unique:members,' . Auth::guard('member')->id()
                ],
                'email'               => 'required|email|validate_unique:members,' . Auth::guard('member')->id(),
                'password'            => 'min:6|nullable',
                'contact_info[phone]' => 'digits:10',
                'password_re_enter'   => 're_enter_password|required_with:password',
            ];
        }

        switch($method){
            default:
                return [
                    'name'                => 'required',
                    'username'            => [
                        'required',
                        'regex:/(^([a-zA-Z0-9_.]+)(\d+)?$)/u',
                        'validate_unique:members'
                    ],
                    'email'               => 'required|email|validate_unique:members',
                    'password'            => 'required|min:6',
                    'contact_info[phone]' => 'digits:10',
                    'password_re_enter'   => 're_enter_password|required_with:password',
                ];
            case 'update':
                return [
                    'name'                => 'required',
                    'username'            => [
                        'required',
                        'regex:/(^([a-zA-Z0-9_.]+)(\d+)?$)/u',
                        'validate_unique:members,' . $this->id,
                    ],
                    'email'               => 'required|email|validate_unique:members,' . $this->id,
                    'password'            => 'min:6|nullable',
                    'contact_info[phone]' => 'digits:10',
                    'password_re_enter'   => 're_enter_password|required_with:password',
                ];
        }
    }

    public function messages(){
        return [
            'required'          => ':attribute' . trans(' can not be null.'),
            'regex'             => ':attribute' . trans(' contains invalid characters.'),
            'email'             => ':attribute' . trans(' must be the email.'),
            'min'               => ':attribute' . trans('  too short.'),
            're_enter_password' => trans('Wrong password'),
            'required_with'     => ':attribute' . trans(' can not be null.'),
            'validate_unique'   => ':attribute' . trans(' was exist.'),
            'digits' => ':attribute' . trans(' must be 10 digits.')
        ];
    }

    public function attributes(){
        return [
            'name'                => trans('Name'),
            'username'            => trans('Username'),
            'email'               => trans('Email'),
            'contact_info[phone]' => trans('Phone'),
            'password'            => trans('Password'),
            'password_re_enter'   => trans('Re-enter Password'),
            'status'              => trans('Re-enter Password'),
        ];
    }
}
