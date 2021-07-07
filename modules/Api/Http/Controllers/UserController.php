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
        $data = $request->only("email", "password");

        if(empty($request->email) || empty($request->password)){
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
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function profile(){
        return User::query()->find($this->auth->id());
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
            'user'         => User::query()->find($this->auth->id()),
            'token_type'   => 'bearer',
            'expires_in'   => $this->auth->factory()->getTTL() * 60,
            'access_token' => $token
        ]);
    }
}
