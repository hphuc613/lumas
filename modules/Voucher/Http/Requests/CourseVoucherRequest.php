<?php

namespace Modules\Voucher\Http\Requests;

use App\AppHelpers\Helper;
use Illuminate\Foundation\Http\FormRequest;

class CourseVoucherRequest extends FormRequest{
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
                    "code"      => "required|validate_unique:course_vouchers",
                    "price"     => "required",
                    "course_id" => "required|check_exist:courses,id",
                    "start_at"  => "required",
                ];
            case "update":
                return [
                    "code"      => "required|validate_unique:course_vouchers," . $this->id,
                    "price"     => "required",
                    "course_id" => "required|check_exist:courses,id",
                    "start_at"  => "required",
                ];
        }
    }

    public function messages(){
        return [
            'required'        => ':attribute' . trans(' can not be empty.'),
            'validate_unique' => ':attribute' . trans(' was exist.'),
            'check_exist'     => ':attribute' . trans(' does not exist.'),
        ];
    }

    public function attributes(){
        return [
            "code"      => trans("Code"),
            "start_at"  => trans("Start day"),
            "price"     => trans("Price"),
            "course_id" => trans("Course"),
        ];
    }
}
