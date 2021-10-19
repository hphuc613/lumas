<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $data = Appointment::query()
                           ->with('store')
                           ->with('member')
                           ->with('user');
        if (isset($request->status)) {
            $data = $data->where('status', $request->status);
        }
        if (isset($request->today) && $request->today == 1) {
            $data = $data->whereDate('time', DB::raw('CURDATE()'));
        }
        if (isset($request->time)) {
            if (!isset($request->today) || (isset($request->today) && $request->today != 1)) {
                $data = $data->whereDate('time', formatDate(strtotime($request->time), "Y-m-d"));
            }
        }
        if (isset($request->user_id)) {
            $data = $data->where('user_id', $request->user_id);
        }
        if (isset($request->member_id)) {
            $data = $data->where('member_id', $request->member_id);
        }

        $item_qty = 10;
        if (isset($request->item_qty)) {
            $item_qty = $request->item_qty;
        }
        $data = $data->orderBy('time', 'desc')->paginate($item_qty);

        return response()->json([
            'lenght' => count($data),
            'status' => 200,
            'data'   => $data
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function detail($id){
        $appointment                               = Appointment::query()
                                                                ->with('store')
                                                                ->with('member')
                                                                ->with('user')
                                                                ->find($id);
        $comment                                   = json_decode($appointment->comment, 1);
        $data['appointment']                       = $appointment->toArray();
        $data['appointment']['comment']            = $comment['comment'] ?? null;
        $data['appointment']['comment_created_at'] = $comment['created_at'] ?? null;
        $data['appointment']['room_name']          = $appointment->room->name ?? null;
        $data['appointment']['instrument_name']    = $appointment->instrument->name ?? null;
        unset($data['appointment']['room_id'], $data['appointment']['instrument_id']);
        $data['appointment']['services'] = $appointment->getServiceList();
        $data['appointment']['courses']  = $appointment->getCourseList();

        return response()->json([
            'status' => 200,
            'data'   => $data['appointment']
        ]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function comment(Request $request, $id){
        $appointment          = Appointment::query()->find($id);
        $appointment->comment = json_encode(["comment"    => $request->comment,
                                             "created_at" => formatDate(time(), 'd-m-Y H:i:s')]);
        $appointment->save();

        return response()->json([
            'status' => 200,
            'data'   => $appointment->toArray()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function remark(Request $request, $id){
        $appointment              = Appointment::query()->find($id);
        $appointment->description = $request->description;
        $appointment->save();

        return response()->json([
            'status' => 200,
            'data'   => $appointment->toArray()
        ]);
    }
}
