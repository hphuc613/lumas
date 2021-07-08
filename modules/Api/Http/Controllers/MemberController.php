<?php

namespace Modules\Api\Http\Controllers;

use App\AppHelpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Api\Http\Requests\ForgotPasswordRequest;
use Modules\Api\Http\Requests\MemberRequest;
use Modules\Member\Model\Member;


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
        if(empty($request->username) || empty($request->password)){
            return response()->json(['error' => trans('Incorrect username or password')]);
        }

        if(!$token = $this->auth->attempt($data)){
            return response()->json(['error' => trans('Your account is inactive. Please contact with admin page to get more information.')], 400);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(){
        $this->auth->logout();
        return response()->json(['message' => trans('Successfully logged out')]);
    }

    /**
     * @param MemberRequest $request
     * @return JsonResponse
     */
    public function validateRegister(MemberRequest $request){
        return response()->json(['message' => 'Validated']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request){
        $data = $request->all();
        unset($data['password_re_enter']);
        $member = new Member($data);
        $member->save();

        return response()->json(['message' => trans('Registered Successfully'), 'client_info' => $data]);
    }

    /**
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function profile(){
        return Member::query()->find($this->auth->id());
    }

    /**
     * @param MemberRequest $request
     * @return JsonResponse
     */
    public function updateProfile(MemberRequest $request){
        $member = Member::query()->where('id', $this->auth->id())->first();
        $data   = $request->all();
        if(empty($request->password)){
            unset($data['password']);
        }
        $member->update($data);

        return response()->json(['message' => trans('Updated Successfully'), 'client_info' => $member]);
    }

    /**
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request){
        $member = Member::query()->where('email', $request->email)->first();

        if(!empty($member)){
            $password = Str::random(6);
            $body     = '';
            $body     .= "<div><p>" . trans("Your password: ") . $password . "</p></div>";
            $body     .= '<div><i><p style="color: red">' . trans("You should change password after login.") . '</p></i></div>';
            $send     = Helper::sendMail($member->email, trans('Reset password'), trans('Reset password'), $body);
            if($send){
                $member->password = $password;
                $member->save();

                return response()->json([
                    'message' => trans('Sent mail successfully.')]);
            }
        }else{
            return response()->json(['error' => trans('Email does not exist in system.')], 400);
        }


        return response()->json(['error' => trans('Cannot send mail.')], 502);
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
            'client_info'  => Member::query()->find($this->auth->id()),
            'token_type'   => 'bearer',
            'expires_in'   => $this->auth->factory()->getTTL() * 60,
            'access_token' => $token
        ]);
    }
}
