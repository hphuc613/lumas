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
use Illuminate\Support\Str;
use Modules\Api\Http\Requests\ForgotPasswordRequest;
use Modules\Api\Http\Requests\MemberRequest;
use Modules\Base\Model\Status;
use Modules\Member\Model\Member;
use Modules\Member\Model\MemberCourse;
use Modules\Member\Model\MemberService;


class MemberController extends Controller{
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
        $this->auth = auth('api-member');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request){
        Helper::apiResponseByLanguage($request);
        $data               = $request->only("phone", "password");
        $data['deleted_at'] = null;
        if (empty($request->phone) || empty($request->password)) {
            return response()->json(['status' => 400, 'error' => trans('Incorrect phone or password')]);
        }
        if (!$token = $this->auth->setTTL(60 * 7 * 24)->attempt($data)) {
            return response()->json(['status' => 400, 'error' => trans('Incorrect phone or password')]);
        }
        $member = $this->auth->user();
        if (!empty($member->deleted_at) || $member->status !== Status::STATUS_ACTIVE) {
            return response()->json([
                'status' => 400,
                'error'  => trans('Your account is inactive. Please contact with admin page to get more information.')
            ]);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token){
        return response()->json([
            'status'       => 200,
            'client_info'  => Member::query()->find($this->auth->id()),
            'token_type'   => 'bearer',
            'expires_in'   => $this->auth->factory()->getTTL() * 60,
            'access_token' => $token
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(){
        $this->auth->logout();
        return response()->json(['status' => 200, 'message' => trans('Successfully logged out')]);
    }

    /**
     * @param MemberRequest $request
     * @return JsonResponse
     */
    public function validateRegister(MemberRequest $request){
        return response()->json(['status' => 200, 'message' => 'Validated']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request){
        $data = $request->all();
        unset($data['password_re_enter']);
        $member              = new Member($data);
        $member->status      = Status::STATUS_ACTIVE;
        $member->verify_code = Str::random(40);
        $member->save();

        return response()->json([
            'status'      => 200,
            'message'     => trans('Registered Successfully'),
            'client_info' => $data
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function profile(){
        $data = Member::query()->find($this->auth->id());
        return response()->json(['status' => 200, 'client_info' => $data]);
    }

    /**
     * @param MemberRequest $request
     * @return JsonResponse
     */
    public function updateProfile(MemberRequest $request){
        $member = Member::query()->where('id', $this->auth->id())->first();
        $data   = $request->all();
        if (empty($request->password)) {
            unset($data['password']);
        }
        $member->update($data);

        return response()->json([
            'status'      => 200,
            'message'     => trans('Updated Successfully'),
            'client_info' => $member
        ]);
    }

    /**
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function validateForgotPassword(ForgotPasswordRequest $request){
        return response()->json(['status' => 200, 'message' => 'Validated']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request){
        $member = Member::query()->where('phone', $request->phone)->first();

        if (!empty($member)) {
            $member->password = $request->password;
            $member->save();

            return response()->json(['status' => 200, 'message' => 'Successfully']);
        }

        return response()->json(['status' => 400, 'error' => trans('Phone does not exist in system.')]);
    }

    /**
     * @param $member_id
     * @return JsonResponse
     */
    public function getServiceList(Request $request, $member_id){
        $data = MemberService::with('service', 'voucher')
                             ->where('member_id', $member_id)
                             ->whereRaw('deduct_quantity < quantity')
                             ->orderBy("created_at", "DESC");
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
     * @param $member_id
     * @return JsonResponse
     */
    public function getServiceDetail($id){
        $data = MemberService::with('member', 'service', 'voucher', 'histories')
                             ->where('id', $id)
                             ->first();

        return response()->json([
            'status' => 200,
            'data'   => $data
        ]);
    }

    /**
     * @param $member_id
     * @return JsonResponse
     */
    public function getCourseList(Request $request, $member_id){
        $data = MemberCourse::with('course', 'voucher')
                            ->where('member_id', $member_id)
                            ->whereRaw('deduct_quantity < quantity')
                            ->orderBy("created_at", "DESC");

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

    /**
     * @param $member_id
     * @return JsonResponse
     */
    public function getCourseDetail($id){
        $data = MemberCourse::with('member', 'course', 'voucher', 'histories')
                            ->where('id', $id)
                            ->first();

        return response()->json([
            'status' => 200,
            'data'   => $data
        ]);
    }


    /**
     * @param \Modules\Member\Http\Requests\MemberRequest $request
     * @return array|string
     */
    public function updateAvatar(MemberRequest $request){
        $member = Member::find($this->auth->id());

        if ($request->hasFile('avatar')) {
            $image = $request->avatar;
            if (file_exists('storage/' . $member->avatar)) {
                unlink('storage/' . $member->avatar);
            }
            $upload_folder = 'upload/member/' . $member->id . '-' . $member->username . '/avatar';
            $image_name    = $member->username . time() . '.png';

            $member->avatar = $upload_folder . '/' . $image_name;
            $member->save();

            $image->storeAs('public/' . $upload_folder, $image_name);
        }

        return response()->json([
            'status' => 200,
            'data'   => $member
        ]);
    }
}
