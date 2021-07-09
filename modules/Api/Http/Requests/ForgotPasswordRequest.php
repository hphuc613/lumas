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
            'email' => 'required|email',
        ];
    }

    public function messages(){
        return [
            'email.required' => trans('The Email can not be empty.'),
            'email.email'    => trans('The Email must be a email.')
        ];
    }

    public function attributes(){
        return [
            'email' => trans('Email'),
        ];
    }
}
