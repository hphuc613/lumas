<?php

namespace Modules\Service\Http\Requests;

use App\AppHelpers\Helper;
use Illuminate\Foundation\Http\FormRequest;

class ServiceTypeRequest extends FormRequest{
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
                    'name'   => 'required|validate_unique:roles',
                    'status' => 'required',
                ];
                break;
            case 'update':
                return [
                    'name'   => 'required|validate_unique:roles,' . $this->id,
                    'status' => 'required',
                ];
                break;
        }
    }

    public function messages(){
        return [
            'required'        => ':attribute' . trans(' can not be null.'),
            'validate_unique' => ':attribute' . trans(' was exist.')
        ];
    }

    public function attributes(){
        return [
            'name'        => trans('Service type name'),
            'status'      => trans('Status'),
            'description' => trans('Description'),
        ];
    }
}
