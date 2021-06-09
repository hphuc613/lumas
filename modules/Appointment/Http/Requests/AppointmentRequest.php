<?php

namespace Modules\Appointment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest{
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
        return [
            'name'      => 'required',
            'member_id' => 'required|check_exist:members,id',
            'store_id'  => 'required|check_exist:stores,id',
        ];
    }

    public function messages(){
        return [
            'required'    => ':attribute' . trans(' cannot be null.'),
            'check_exist' => ':attribute' . trans(' does not exist.')
        ];
    }

    public function attributes(){
        return [
            'name'      => trans('Subject'),
            'member_id' => trans('Client'),
            'store_id' => trans('Store')
        ];
    }
}
