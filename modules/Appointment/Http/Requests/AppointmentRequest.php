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
        switch($method) {
            default:
                return [
                    'member_id'     => 'required|check_exist:members,id',
                    'store_id'      => 'required|check_exist:stores,id',
                    'time'          => 'required',
                    'assign_more'   => 'array|max:3',
                    'room_id'       => 'array|max:4',
                    'instrument_id' => 'array|max:4',
                    'user_id'       => 'required'
                ];
            case 'update':
                return [
                    'member_id'     => 'required|check_exist:members,id',
                    'store_id'      => 'required|check_exist:stores,id',
                    'end_time'      => 'nullable|after:time',
                    'assign_more'   => 'array|max:3',
                    'room_id'       => 'array|max:4',
                    'instrument_id' => 'array|max:4',
                    'user_id'       => 'required'
                ];
        }
    }

    public function messages(){
        return [
            'required'          => ':attribute' . trans(' cannot be null.'),
            'check_exist'       => ':attribute' . trans(' does not exist.'),
            'check_past'        => ':attribute' . trans(' is not in the past.'),
            'after'             => ':attribute' . trans(' must be a date after Time.'),
            'assign_more.max'   => ':attribute' . trans(' may not have more than 3 staffs.'),
            'instrument_id.max' => ':attribute' . trans(' may not have more than 4 instruments.'),
            'room_id.max'       => ':attribute' . trans(' may not have more than 4 rooms.')
        ];
    }

    public function attributes(){
        return [
            'member_id'     => trans('Client'),
            'store_id'      => trans('Store'),
            'time'          => trans('Time'),
            'end_time'      => trans('Check Out Time'),
            'user_id'       => trans('Staff'),
            'assign_more'   => trans('Assign more'),
            'instrument_id' => trans('Instrument'),
            'room_id'       => trans('Room'),
        ];
    }
}
