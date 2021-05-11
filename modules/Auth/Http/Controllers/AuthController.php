<?php

namespace Modules\Auth\Http\Controllers;

use App\AppHelpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Modules\Base\Model\Status;
use Modules\User\Model\User;


class AuthController extends Controller {

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        # parent::__construct();
    }

    /**
     * @param Request $request
     *
     * @return Factory|RedirectResponse|View
     */
    public function getLogin(Request $request) {
        if(Auth::check()) {
            return redirect()->route("dashboard");
        }

        return view('Auth::backend.login');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function postLogin(Request $request) {

        $login = [
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ];
        $request->session()->put('email', $request->input('email'));
        if(Auth::guard()->attempt($login, $request->has('remember'))) {
            if(Auth::user()->status == Status::STATUS_ACTIVE && empty(Auth::user()->deleted_at)  && (Auth::user()->getRoleAttribute()->status ?? NULL) == Status::STATUS_ACTIVE ) {
                return redirect()->route('dashboard');
            }
            $request->session()->flash('error',
                                       trans('Your account is inactive. Please contact with admin page to get more information.'));
            return $this->logout();
        } else {
            $request->session()->flash('error', trans('Incorrect username or password'));
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function logout() {
        if(Auth::check()) {
            session('email', Auth::user()->email);
            Auth::logout();
        }
        return redirect()->route('get.login.admin');
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function forgotPassword(Request $request) {
        if($request->post()) {
            $user = User::where('email', $request->email)->first();
            if(!empty($user)) {
                $password = substr(md5(microtime()), rand(0, 26), 6);
                $body     = '';
                $body     .= "<div><p>" . trans("Your password: ") . $password . "</p></div>";
                $body     .= '<div><i><p style="color: red">' . trans("You should change password after login.") . '</p></i></div>';
                $send     = Helper::sendMail($user->email, trans('Reset password'), trans('Reset password'), $body);
                if($send) {
                    $user->password = $password;
                    $user->save();
                    $request->session()->flash('success', trans('Send email successfully. Please check your email'));
                }else{
                    $request->session()->flash('error', trans('Can not send email. Please contact with admin.'));
                }
            } else {
                $request->session()->flash('error', trans('Your email not exist.'));
            }

            return redirect()->route('get.login.admin');
        }

        return view('Auth::backend.forgot_password');
    }
}
