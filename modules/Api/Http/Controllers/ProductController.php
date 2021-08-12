<?php

namespace Modules\Api\Http\Controllers;

use App\AppHelpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Appointment\Model\Appointment;
use Modules\Member\Model\MemberCourse;
use Modules\Member\Model\MemberCourseHistory;
use Modules\Member\Model\MemberService;
use Modules\Member\Model\MemberServiceHistory;


class ProductController extends Controller{
    /**
     * @var Factory|Guard|StatefulGuard|Application|null
     */
    private $auth;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->auth = auth('api-user');
    }


    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function postUseProduct(Request $request){
        $product = Helper::segment(1);
        if ($product === 'service') {
            $model              = MemberService::query();
            $status_progressing = MemberService::PROGRESSING_STATUS;
        } else {
            $model              = MemberCourse::query();
            $status_progressing = MemberCourse::PROGRESSING_STATUS;
        }

        $member_product = $model->where('code', $request->code)->first();
        if (empty($member_product) || $member_product->quantity == $member_product->deduct_quantity) {
            return response()->json([
                'status' => 404,
                'error'  => trans("This product was not found.")
            ]);
        }

        if (!$member_product->member->getAppointmentInProgressing()) {
            return response()->json([
                'status' => 405,
                'error'  => trans("Please check in an appointment."),
            ]);
        }

        $member_product->status     = $status_progressing;
        $member_product->updated_by = $this->auth->id();
        $member_product->save();

        return response()->json([
            'status' => 200,
            'data'   => $member_product
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function postStopUseProduct(Request $request){
        $product = Helper::segment(1);
        if ($product === 'service') {
            $model         = MemberService::query();
            $status_finish = MemberService::COMPLETED_STATUS;
        } else {
            $model         = MemberCourse::query();
            $status_finish = MemberCourse::COMPLETED_STATUS;
        }

        $member_product = $model->where('code', $request->code)->first();
        if (empty($member_product)) {
            return response()->json([
                'status' => 404,
                'data'   => trans("This product was not found.")
            ]);
        }

        $member_product->status = $status_finish;
        $member_product->save();

        return response()->json([
            'status' => 200,
            'data'   => $member_product
        ]);
    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function postESignProduct(Request $request){

        $data = $request->all();
        unset($data['code']);
        unset($data['user_id']);

        $product = Helper::segment(1);
        if ($product === 'service') {
            $model         = MemberService::query();
            $status_finish = MemberService::COMPLETED_STATUS;
        } else {
            $model         = MemberCourse::query();
            $status_finish = MemberCourse::COMPLETED_STATUS;
        }

        $member_product = $model->where('code', $request->code)->first();
        if (empty($member_product)) {
            return response()->json([
                'status' => 404,
                'data'   => trans("This product was not found.")
            ]);
        }

        $appointment = Appointment::query()
                                  ->where('member_id', $member_product->member_id)
                                  ->where('status', Appointment::PROGRESSING_STATUS)
                                  ->first();

        if ($member_product->status === $status_finish) {
            return response()->json([
                'status' => 404,
                'error'  => 'This product is not in use'
            ]);
        }

        /** E-sign*/
        $data['updated_by']     = $request->user_id;
        $data['start']          = formatDate(strtotime($member_product->updated_at), 'Y-m-d H:i:s');
        $data['end']            = formatDate(time(), 'Y-m-d H:i:s');
        $data['appointment_id'] = $appointment->id;
        if ($product === 'service') {
            $data['member_service_id'] = $member_product->id;
            $history                   = new MemberServiceHistory($data);
        } else {
            $data['member_course_id'] = $member_product->id;
            $history                  = new MemberCourseHistory($data);
        }
        $history->save();

        /**  Reduce the quantity of */
        $member_product->deduct_quantity += 1;
        $member_product->status          = $status_finish;
        $member_product->save();

        return response()->json([
            'status' => 200,
            'data'   => [
                'member_product' => $member_product,
                'history'        => $history
            ],
        ]);
    }

    /**
     * @param Request $request
     * @param $user_id
     * @return JsonResponse
     */
    public function getServiceUsingList(Request $request, $user_id){
        $data = MemberService::with('service', 'voucher')
                             ->where('updated_by', $user_id)
                             ->where('status', MemberService::PROGRESSING_STATUS)
                             ->orderBy("updated_at");

        if (isset($request->key)) {
            $data = $data->whereHas('service', function($query) use ($request){
                return $query->where('name', 'LIKE', '%' . $request->key . '%');
            });
            $data = $data->orWhere('code', 'LIKE', '%' . $request->key . '%');
        }

        $data = $data->get();

        return response()->json([
            'status' => 200,
            'data'   => $data
        ]);
    }

    /**
     * @param Request $request
     * @param $user_id
     * @return JsonResponse
     */
    public function getCourseUsingList(Request $request, $user_id){
        $data = MemberCourse::with('course', 'voucher')
                            ->where('updated_by', $user_id)
                            ->where('status', MemberCourse::PROGRESSING_STATUS)
                            ->orderBy("updated_at");

        if (isset($request->key)) {
            $data = $data->whereHas('course', function($query) use ($request){
                return $query->where('name', 'LIKE', '%' . $request->key . '%');
            });
            $data = $data->orWhere('code', 'LIKE', '%' . $request->key . '%');
        }

        $data = $data->get();

        return response()->json([
            'status' => 200,
            'data'   => $data
        ]);
    }

}
