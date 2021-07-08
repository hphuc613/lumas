<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Appointment\Model\Appointment;


class AppointmentController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }


    /**
     * @param Request $request
     * @return JsonResponse
     *
     * https:domain.hp/api/appointment/list?user_id=5&member_id=1
     */
    public function list(Request $request){
        $data = Appointment::query();
        if(isset($request->user_id)){
            $data = $data->where('user_id', $request->user_id);
        }
        if(isset($request->member_id)){
            $data = $data->where('member_id', $request->member_id);
        }

        $data = $data->get();

        return response()->json([
            'status'       => 200,
            'appointments' => $data
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function detail($id){
        $appointment                    = Appointment::query()->find($id);
        $data['appointment']            = $appointment->toArray();
        $data['appointment']['service'] = $appointment->getServiceList();
        $data['appointment']['course']  = $appointment->getCourseList();

        return response()->json([
            'status'      => 200,
            'appointment' => $data['appointment']
        ]);
    }
}
