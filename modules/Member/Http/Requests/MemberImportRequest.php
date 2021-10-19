<?php

namespace Modules\Member\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberImportRequest extends FormRequest{

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
            'file' => 'required|mimes:xlsx,xls,csv,ods',
        ];
    }

    public function messages(){
        return [
            'required' => ':attribute' . trans(' can not be empty.'),
            'mimes'    => ':attribute' .
                          trans(' extension must be one of the following: xlsx, xls, csv, ods.'),
        ];
    }

    public function attributes(){
        return [
            'file' => trans('File'),
        ];
    }
}
