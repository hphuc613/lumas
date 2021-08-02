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
     * @var Request
     */
    private $request;

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
        $data = $request->only("username", "password");
        if (empty($request->username) || empty($request->password)) {
            return response()->json(['status' => 400, 'error' => trans('Incorrect username or password')]);
        }
        if (!$token = $this->auth->attempt($data)) {
            return response()->json(['status' => 400, 'error' => trans('Incorrect username or password')]);
        }
        $member = $this->auth->user();
        if (!empty($member->deleted_at) && $member->status !== Status::STATUS_ACTIVE) {
            return response()->json([
                'status' => 400,
                'error'  => trans('Your account is inactive. Please contact with admin page to get more information.')
            ]);
        }

        if ($member->status == Status::STATUS_PENDING) {
            return response()->json([
                'status' => 402,
                'error'  => trans('Please verify your account.')
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
            'status' => 200,
            'client_info' => Member::query()->find($this->auth->id()),
            'token_type' => 'bearer',
            'expires_in' => $this->auth->factory()->getTTL() * 60,
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
        $member->status      = Status::STATUS_PENDING;
        $member->verify_code = Str::random(40);
        $member->save();
        $body = '<div><a style="background-color: #4CAF50;
                              border: none;
                              color: white;
                              padding: 10px 32px;
                              text-align: center;
                              text-decoration: none;
                              display: inline-block;
                              font-size: 16px;" href="' . route('frontend.get.success_register', $member->verify_code) .
                '">' . trans("Verify") . '</a></div>';
        $send = Helper::sendMail($member->email, trans('LUMAS - Register Account'), 'Verify Account', $body);
        if (!$send) {
            return response()->json([
                'status'  => 400,
                'message' => trans('Can not send email. Please contact to administrator.')
            ]);
        }

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
        $data = $request->all();
        if (empty($request->password)) {
            unset($data['password']);
        }
        $member->update($data);

        return response()->json([
            'status' => 200,
            'message' => trans('Updated Successfully'),
            'client_info' => $member
        ]);
    }

    /**
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request){
        $member = Member::query()->where('email', $request->email)->first();

        if (!empty($member)) {
            $password = Str::random(6);
            $body = '';
            $body .= "<div><p>" . trans("Your password: ") . $password . "</p></div>";
            $body .= '<div><i><p style="color: red">' . trans("You should change password after login.") .
                     '</p></i></div>';
            $send = Helper::sendMail($member->email, trans('Reset password'), trans('Reset password'), $body);
            if ($send) {
                $member->password = $password;
                $member->save();

                return response()->json(['status' => 200, 'message' => trans('Sent mail successfully.')]);
            }
        } else {
            return response()->json(['status' => 400, 'error' => trans('Email does not exist in system.')]);
        }


        return response()->json(['status' => 502, 'error' => trans('Cannot send mail.')]);
    }

    /**
     * @param $member_id
     * @return JsonResponse
     */
    public function getServiceList($member_id){
        $data = MemberService::with('service', 'voucher')->where('member_id', $member_id)->get();

        return response()->json([
            'status' => 200,
            'data' => $data
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
            'data' => $data
        ]);
    }

    /**
     * @param $member_id
     * @return JsonResponse
     */
    public function getCourseList($member_id){
        $data = MemberCourse::with('course', 'voucher')->where('member_id', $member_id)->get();

        return response()->json([
            'status' => 200,
            'data' => $data
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
            'data' => $data
        ]);
    }
}
