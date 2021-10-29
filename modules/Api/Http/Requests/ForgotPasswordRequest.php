<?php

namespace Modules\Api\Http\Requests;

class ForgotPasswordRequest extends ApiRequest{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'phone'            => 'required|check_exist:members,phone',
            'password'         => 'min:6|required|regex:/^.*(?=.{2,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/',
            'confirm_password' => 're_enter_password|required_with:password'
        ];
    }

    public function messages(){
        return [
            'phone.required'    => trans('The Phone can not be empty.'),
            'check_exist'       => ':attribute' . trans(' does not exist.'),
            'password.required' => trans('The Password can not be empty.'),
            'password.regex'    => trans('The Password must at least 1 number or 1 english letter'),
            'min'               => trans('The Password too short.'),
            're_enter_password' => trans('The Wrong password'),
            'required_with'     => trans('The Confirm Password can not be empty.'),
        ];
    }

    public function attributes(){
        return [
            'phone'            => trans('Phone'),
            'password'         => trans('Password'),
            'confirm_password' => trans('Confirm Password'),
        ];
    }
}
