<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Base\Model\Status;
use Modules\Notification\Model\NotificationModel;
use Modules\Setting\Model\AppointmentSetting;

class NotificationController extends Controller{
    /**
     * @var Factory|Guard|StatefulGuard
     */
    private $auth;

    /**
     * @var Factory|Guard|StatefulGuard
     */
    private $client_auth;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->auth        = auth('api-user');
        $this->client_auth = auth('api-member');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getList(Request $request){
        $data = NotificationModel::query();

        if (isset($request->member_id)) {
            $data = $data->where('data->member_id', $request->member_id);
        }

        if (isset($request->user_id)) {
            $data = $data->where('data->user_id', $request->user_id);
        }

        $data = $data->where('data->status', Status::STATUS_ACTIVE)
                     ->orderBy('created_at', 'DESC')
                     ->get();


        $appointments          = null;
        $count_client_not_read = 0;
        $count_user_not_read   = 0;
        foreach($data as $key => $value) {
            $appointment        = json_decode($value->data, 1);
            $appointments[$key] = $appointment;
            if (empty($value->read_at)) {
                $count_user_not_read++;
            }

            if (!isset($appointment['client_read_at']) ||
                (isset($appointment['client_read_at']) && empty($appointment['client_read_at']))) {
                $count_client_not_read++;
            }
        }

        return response()->json([
            'status'          => 200,
            'not_read_user'   => $count_user_not_read,
            'not_read_client' => $count_client_not_read,
            'data'            => $appointments
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getUserNext(){
        $timer       = (int)AppointmentSetting::getValueByKey(AppointmentSetting::TIMER) * 60;
        $data        = NotificationModel::query()
                                        ->where('notifiable_id', $this->auth->id())
                                        ->where('data->user_time_show', null)
                                        ->where('data->time', '>', formatDate(time(), 'Y-m-d H:i'))
                                        ->where('data->time', '<=', formatDate(time() + $timer, 'Y-m-d H:i'))
                                        ->first();
        $appointment = null;
        if (!empty($data)) {
            $appointment                   = json_decode($data->data, 1);
            $appointment['status']         = Status::STATUS_ACTIVE;
            $appointment['user_time_show'] = formatDate(time(), 'd-m-Y H:i:s');
            $data->data                    = json_encode($appointment);
            $data->save();
        }

        return response()->json([
            'status' => 200,
            'data'   => $appointment
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getMemberNext(){
        $timer       = (int)AppointmentSetting::getValueByKey(AppointmentSetting::TIMER) * 60;
        $data        = NotificationModel::query()
                                        ->where('data->member_id', $this->client_auth->id())
                                        ->where('data->client_time_show', null)
                                        ->where('data->time', '>', formatDate(time(), 'Y-m-d H:i'))
                                        ->where('data->time', '<=', formatDate(time() + $timer, 'Y-m-d H:i'))
                                        ->first();
        $appointment = null;
        if (!empty($data)) {
            $appointment                     = json_decode($data->data, 1);
            $appointment['status']           = Status::STATUS_ACTIVE;
            $appointment['client_time_show'] = formatDate(time(), 'd-m-Y H:i:s');
            $data->data                      = json_encode($appointment);
            $data->save();
        }

        return response()->json([
            'status' => 200,
            'data'   => $appointment
        ]);
    }

    /**
     * @param $apm_id
     * @param $user_id
     * @param $user_mobile
     * @return JsonResponse
     */
    public function postReadNotification($apm_id, $user_id, $user_mobile){
        $data = NotificationModel::query()
                                 ->where('data->appointment_id', $apm_id)
                                 ->where('data->user_id', $user_id)
                                 ->first();

        if (!empty($data)) {
            $appointment           = json_decode($data->data, 1);
            $appointment['status'] = Status::STATUS_ACTIVE;
            if ($user_mobile == 'user') {
                $data->read_at               = formatDate(time(), 'd-m-Y H:i:s');
                $appointment['user_read_at'] = formatDate(time(), 'd-m-Y H:i:s');
            } else {
                $appointment['client_read_at'] = formatDate(time(), 'd-m-Y H:i:s');
            }
            $data->data = json_encode($appointment);
            $data->save();

            return response()->json([
                'status' => 200,
            ]);
        }

        return response()->json([
            'status' => 404,
            'error'  => 'Cannot find notification'
        ]);
    }
}
