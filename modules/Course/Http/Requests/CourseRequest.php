<?php

namespace Modules\Course\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest{
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
            'name'        => 'required',
            'category_id' => 'required|check_exist:course_categories,id',
            'status'      => 'required'
        ];
    }

    public
    function messages(){
        return [
            'required'    => ':attribute' . trans(' can not be null.'),
            'check_exist' => ':attribute' . trans(' does not exist.'),
        ];
    }

    public
    function attributes(){
        return [
            'name'        => trans('Course name'),
            'status'      => trans('Status'),
            'category_id' => trans('Course Category')
        ];
    }
}
