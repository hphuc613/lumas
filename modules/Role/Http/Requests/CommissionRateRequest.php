<?php

namespace Modules\Role\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommissionRateRequest extends FormRequest{
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
            'target' => 'required|numeric|min:1',
            'rate'   => 'numeric|min:1',
            'bonus'  => 'numeric|min:1'
        ];
    }

    public function messages(){
        return [
            'required' => ':attribute' . trans(' can not be empty.'),
            'numeric'  => ':attribute' . trans(' must be a numeric.'),
            'min'      => ':attribute' . trans(' must be greater than 0')
        ];
    }

    public function attributes(){
        return [
            'target' => trans('Target'),
            'rate'   => trans('Rate'),
            'bonus'   => trans('Bonus'),
        ];
    }
}
