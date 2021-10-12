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
use Modules\Base\Model\Status;
use Modules\User\Model\User;


class UserController extends Controller{
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
        $this->auth = auth('api-user');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request){
        Helper::apiResponseByLanguage($request);
        $data               = $request->only("email", "password");
        $data['deleted_at'] = null;

        if(empty($request->email) || empty($request->password)){
            return response()->json(['status' => 400, 'error' => trans('Incorrect username or password')]);
        }
        if(!$token = $this->auth->setTTL(60 * 7 * 24)->attempt($data)){
            return response()->json(['status' => 400, 'error' => trans('Incorrect username or password')]);
        }
        $user = $this->auth->user();
        if($user->status !== Status::STATUS_ACTIVE && !empty($user->deleted_at) && ($user->getRoleAttribute()->status ?? NULL) !== Status::STATUS_ACTIVE){
            return response()->json(['status' => 400,
                                     'error'  => trans('Your account is inactive. Please contact with admin page to get more information.')]);
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
        return response()->json(['status' => 200, 'message' => trans('Successfully logged out')]);
    }

    /**
     * @return JsonResponse
     */
    public function profile(){
        $data = User::query()->find($this->auth->id());
        $role = $data->getRoleAttribute()->toArray();
        $data = $data->toArray();
        unset($data['roles']);
        unset($role['commission_rates']);
        $data['role'] = $role;

        return response()->json(['status' => 200, 'user' => $data]);
    }

    /**
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request){
        $user = User::where('email', $request->email)->first();

        if(!empty($user)){
            $password = Str::random(6);
            $body     = '';
            $body     .= "<div><p>" . trans("Your password: ") . $password . "</p></div>";
            $body     .= '<div><i><p style="color: red">' . trans("You should change password after login.") . '</p></i></div>';
            $send     = Helper::sendMail($user->email, trans('Reset password'), trans('Reset password'), $body);
            if($send){
                $user->password = $password;
                $user->save();

                return response()->json(['status' => 200, 'message' => trans('Sent mail successfully.')]);
            }
        }else{
            return response()->json(['status' => 400, 'error' => trans('Email does not exist in system.')]);
        }


        return response()->json(['status' => 502, 'error' => trans('Cannot send mail.')]);
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
            'user'         => User::query()->find($this->auth->id()),
            'token_type'   => 'bearer',
            'expires_in'   => $this->auth->factory()->getTTL() * 60,
            'access_token' => $token
        ]);
    }
}
