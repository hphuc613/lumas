<?php

namespace Modules\Member\Http\Requests;

use App\AppHelpers\Helper;
use Illuminate\Foundation\Http\FormRequest;

class MemberServiceRequest extends FormRequest{

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
                    'service_id' => 'required|check_exist:services,id',
                    'member_id'  => 'required|check_exist:members,id',
                    'voucher_id' => 'nullable|check_exist:service_vouchers,id',
                    'quantity'   => 'required|numeric',
                ];
            case "edit-service":
                return [
                    'service_id' => 'required|check_exist:services,id',
                    'member_id'  => 'required|check_exist:members,id',
                    'voucher_id' => 'nullable|check_exist:service_vouchers,id',
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
            'service_id' => trans('Service'),
            'quantity'   => trans('Quantity'),
            'Voucher'    => trans('Quantity'),
            'member_id'  => trans('Client')
        ];
    }
}
