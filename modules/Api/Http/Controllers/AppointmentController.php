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
        if (isset($request->member_id)) {
            $data = $data->where('member_id', $request->member_id);
        }
        if (isset($request->user_id)) {
            $data = $data->where('user_id', $request->user_id)
                         ->orWhereHas('staffs', function($q) use ($request){
                             $q->where('user_id', $request->user_id);
                         });
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
        $appointment                = Appointment::query()
                                                 ->with('store')
                                                 ->with('member')
                                                 ->with('user')
                                                 ->find($id);
        $comment                    = json_decode($appointment->comment, 1);
        $data                       = $appointment->toArray();
        $data['comment']            = $comment['comment'] ?? null;
        $data['comment_created_at'] = $comment['created_at'] ?? null;
        $data['remarks']            = $comment['remarks'] ?? null;
        $data['remarks_created_at'] = $comment['remarks_created_at'] ?? null;
        $data['room_name']          = implode(', ', $appointment->getRooms(true) ?? []);
        $data['instrument_name']    = implode(', ', $appointment->getInstruments(true) ?? []);
        $data['assign_more']        = implode(', ', $appointment->staffs->pluck('name')->toArray());
        unset($data['room_id'], $data['instrument_id'], $data['notify_created']);
        $data['services'] = $appointment->getServiceList();
        $data['assign']   = $appointment->getAssign();
        $data['courses']  = $appointment->getCourseList();

        return response()->json([
            'status' => 200,
            'data'   => $data
        ]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function comment(Request $request, $id){
        $appointment           = Appointment::query()->find($id);
        $comment               = json_decode($appointment->comment, 1);
        $comment['comment']    = $request->comment;
        $comment['created_at'] = formatDate(time(), 'd-m-Y H:i:s');
        $appointment->comment  = json_encode($comment);
        $appointment->save();

        return response()->json([
            'status' => 200,
            'data'   => json_decode($appointment->comment, 1)
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function remark(Request $request, $id){
        $appointment = Appointment::query()->find($id);

        if (!empty($appointment)) {
            if (utf8_word_count($request->remarks) > 30) {
                return response()->json([
                    'status'  => 400,
                    'message' => trans("Remarks can only be up to 30 words")
                ]);
            }

            $comment                       = json_decode($appointment->comment, 1);
            $comment['remarks']            = $request->remarks;
            $comment['remarks_created_at'] = formatDate(time(), 'd-m-Y H:i:s');
            $appointment->comment          = json_encode($comment);
            $appointment->save();

            return response()->json([
                'status' => 200,
                'data'   => json_decode($appointment->comment, 1)
            ]);
        }

        return response()->json([
            'status' => 404,
        ]);
    }
}
