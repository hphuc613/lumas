<?php

namespace Modules\Appointment\Http\Requests;

use App\AppHelpers\Helper;
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
        $method = Helper::segment(2);
        switch($method){
            default:
                return [
                    'name'       => 'required',
                    'member_id'  => 'required|check_exist:members,id',
                    'service_id' => 'required|check_exist:services,id',
                    'store_id'   => 'required|check_exist:stores,id',
                    'time'       => 'required|check_past',
                ];
            case "update":
                return [];
        }
    }

    public function messages(){
        return [
            'required'    => ':attribute' . trans(' cannot be null.'),
            'check_exist' => ':attribute' . trans(' does not exist.'),
            'check_past'  => ':attribute' . trans(' is not in the past.'),
        ];
    }

    public function attributes(){
        return [
            'name'       => trans('Subject'),
            'member_id'  => trans('Client'),
            'service_id' => trans('Service'),
            'store_id'   => trans('Store'),
            'time'       => trans('Time'),
        ];
    }
}
