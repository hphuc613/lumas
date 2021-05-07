<?php

namespace Modules\User\Http\Requests;

use App\AppHelpers\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserValidation extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $method = Helper::segment(2);
        if(Helper::segment(1) === 'profile') {
            return ['name'              => 'required',
                    'email'             => 'required|email|validate_unique:users,' . Auth::guard()->id(),
                    'password'          => 'min:6|nullable',
                    'password_re_enter' => 're_enter_password|required_with:password',];
        }

        switch($method) {
            default:
                return ['name'              => 'required',
                        'email'             => 'required|email|validate_unique:users',
                        'role_id'           => 'check_exist:roles,id',
                        'password'          => 'required|min:6',
                        'password_re_enter' => 're_enter_password|required_with:password',];
            case 'update':
                return ['name'              => 'required',
                        'email'             => 'required|email|validate_unique:users,' . $this->id,
                        'role_id'           => 'check_exist:roles,id',
                        'password'          => 'min:6|nullable',
                        'password_re_enter' => 're_enter_password|required_with:password',];
        }
    }

    public function messages() {
        return ['required'          => ':attribute' . trans(' can not be null.'),
                'email'             => ':attribute' . trans(' must be the email.'),
                'min'               => ':attribute' . trans('  too short.'),
                're_enter_password' => trans('Wrong password'),
                'required_with'     => ':attribute' . trans(' can not be null.'),
                'validate_unique'   => ':attribute' . trans(' was exist.'),
                'check_exist'       => ':attribute' . trans(' does not exist.'),];
    }

    public function attributes() {
        return ['name'              => trans('Name'),
                'email'             => trans('Email'),
                'password'          => trans('Password'),
                'password_re_enter' => trans('Re-enter Password'),
                'status'            => trans('Re-enter Password'),
                'role_id'           => trans('Role')];
    }
}
