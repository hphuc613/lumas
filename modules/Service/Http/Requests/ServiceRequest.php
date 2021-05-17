<?php

namespace Modules\Service\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest{
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
            'name'    => 'required',
            'price'   => 'required',
            'status'  => 'required',
            'type_id' => 'required|check_exist:service_types,id',
        ];
    }

    public function messages(){
        return [
            'required'    => ':attribute' . trans(' can not be null.'),
            'check_exist' => ':attribute' . trans(' does not exist.'),
        ];
    }

    public function attributes(){
        return [
            'name'    => trans('Service name'),
            'price'   => trans('Price'),
            'status'  => trans('Status'),
            'type_id' => trans('Service Type')
        ];
    }
}
