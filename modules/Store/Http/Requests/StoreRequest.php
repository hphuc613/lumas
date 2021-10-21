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
        switch($method) {
            default:
                return [
                    'name'            => 'required|validate_unique:stores',
                    'open_close_time' => 'required',
                    'address'         => 'required',
                    'phone'           => 'nullable|digits:8'
                ];
            case "update":
                return [
                    'name'            => 'required|validate_unique:stores,' . $this->id,
                    'open_close_time' => 'required',
                    'address'         => 'required',
                    'phone'           => 'nullable|digits:8'
                ];
        }
    }

    public function messages(){
        return [
            'required'        => ':attribute' . trans(' can not be empty.'),
            'validate_unique' => ':attribute' . trans(' was exist.'),
            'digits'          => ':attribute' . trans(' must be 8 digits.'),
        ];
    }

    public function attributes(){
        return [
            'name'            => trans('Name'),
            'open_close_time' => trans('Open/Close Time'),
            'address'         => trans('Address'),
            'phone'           => trans('Phone')
        ];
    }
}
