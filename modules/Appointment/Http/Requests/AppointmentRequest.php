<?php

namespace Modules\Appointment\Http\Requests;

use App\AppHelpers\Helper;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest{
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
                return [];
                break;
            case "update":
                return [];
                break;
        }
    }

    public function messages(){
        return [];
    }

    public function attributes(){
        return [];
    }
}
