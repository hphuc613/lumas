<?php

namespace Modules\Member\Http\Requests;

use App\AppHelpers\Helper;
use Illuminate\Foundation\Http\FormRequest;

class MemberCourseRequest extends FormRequest{

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
                    'course_id'  => 'required|check_exist:courses,id',
                    'member_id'  => 'required|check_exist:members,id',
                    'voucher_id' => 'nullable|check_exist:course_vouchers,id',
                    'quantity'   => 'required|numeric',
                ];
            case "edit-course":
                return [
                    'course_id'  => 'required|check_exist:courses,id',
                    'member_id'  => 'required|check_exist:members,id',
                    'voucher_id' => 'nullable|check_exist:course_vouchers,id',
                    'quantity'   => 'nullable|numeric',
                ];
        }
    }

    public function messages(){
        return [
            'required'    => ':attribute' . trans(' can not be null.'),
            'numeric'     => ':attribute' . trans(' must be a numeric.'),
            'check_exist' => ':attribute' . trans(' does not exist.'),
        ];
    }

    public function attributes(){
        return [
            'course_id'  => trans('Course'),
            'quantity'   => trans('Quantity'),
            'voucher_id' => trans('Voucher'),
            'member_id'  => trans('Client')
        ];
    }
}
