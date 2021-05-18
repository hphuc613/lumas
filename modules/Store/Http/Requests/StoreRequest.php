<?php

namespace Modules\Store\Http\Requests;

use App\AppHelpers\Helper;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest{
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
                    'name'            => 'required|validate_unique:stores',
                    'open_close_time' => 'required',
                    'address'         => 'required'
                ];
            case "update":
                return [
                    'name'            => 'required|validate_unique:stores,' . $this->id,
                    'open_close_time' => 'required',
                    'address'         => 'required'
                ];
        }
    }

    public function messages(){
        return [
            'required'        => ':attribute' . trans(' can not be null.'),
            'validate_unique' => ':attribute' . trans(' was exist.'),
        ];
    }

    public function attributes(){
        return [
            'name'            => 'Store name',
            'open_close_time' => 'Open/Close time',
            'address'         => 'Address'
        ];
    }
}
