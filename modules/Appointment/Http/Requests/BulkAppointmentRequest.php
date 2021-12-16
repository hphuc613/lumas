<?php

namespace Modules\Appointment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkAppointmentRequest extends FormRequest{
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
            'member_id'   => 'required|check_exist:members,id',
            'store_id'    => 'required|check_exist:stores,id',
            'time'        => 'required',
            'from'        => 'required',
            'to'          => 'required|after:from',
            'day_of_week' => 'required',
            'assign_more' => 'array|max:3',
            'user_id'     => 'required'
        ];
    }

    public function messages(){
        return [
            'required'    => ':attribute' . trans(' cannot be null.'),
            'check_exist' => ':attribute' . trans(' does not exist.'),
            'check_past'  => ':attribute' . trans(' is not in the past.'),
            'after'       => ':attribute' . trans(' must be a date after Start Date.'),
            'assign_more.max' => ':attribute' . trans(' may not have more than 3 staffs.')
        ];
    }

    public function attributes(){
        return [
            'member_id'   => trans('Client'),
            'store_id'    => trans('Store'),
            'time'        => trans('Time'),
            'from'        => trans('Start Date'),
            'to'          => trans('End Date'),
            'day_of_week' => trans('Day of Week'),
            'user_id'     => trans('Staff'),
            'assign_more' => trans('Assign more')
        ];
    }
}
