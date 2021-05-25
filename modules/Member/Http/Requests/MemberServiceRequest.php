<?php

namespace Modules\Member\Http\Requests;

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
        return [
            'service_id' => 'required|check_exist:services,id',
            'member_id'  => 'required|check_exist:members,id',
            'voucher_id' => 'nullable|check_exist:vouchers,id',
            'quantity'   => 'required|numeric',
        ];
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
