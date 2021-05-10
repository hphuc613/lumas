<?php

namespace Modules\Auth\Http\Controllers;

use App\AppHelpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Base\Model\Status;
use Modules\Member\Model\Member;

/**
 * Class AuthMemberController
 * @package Modules\Auth\Http\Controllers
 */
class AuthMemberController extends Controller{

    /**
     * @param Request $request
     *
     * @return string
     */
    public function getLogin(Request $request){
        if(Auth::guard('member')->check()){
            return redirect()->route("frontend.dashboard");
        }

        return view('Auth::frontend.login');
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function postLogin(Request $request){
        $login = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];
        $request->session()->put('username', $request->input('username'));
        if(Auth::guard('member')->attempt($login, $request->has('remember'))){
            if(Auth::guard('member')->user()->status == Status::STATUS_ACTIVE){
                return redirect()->route("frontend.dashboard");
            }
            $request->session()->flash('error',
                                       trans('Your account is inactive. Please contact with admin page to get more information.'));
            return $this->logout();
        }else{
            $request->session()->flash('error', trans('Incorrect username or password'));
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function logout(){
        if(Auth::guard('member')->check()){
            session('username', Auth::guard('member')->user()->username);
            Auth::guard('member')->logout();
        }
        return redirect()->route('frontend.get.login.member');
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function forgotPassword(Request $request){
        if($request->post()){
            $member = Member::where('email', $request->email)->first();
            if(!empty($member)){
                $password = substr(md5(microtime()), rand(0, 26), 6);
                $body     = '';
                $body     .= "<div><p>" . trans("Your password: ") . $password . "</p></div>";
                $body     .= '<div><i><p style="color: red">' . trans("You should change password after login.") . '</p></i></div>';
                $send     = Helper::sendMail($member->email, trans('Reset password'), trans('Reset password'), $body);
                if($send){
                    $member->password = $password;
                    $member->save();
                    $request->session()->flash('success', trans('Send email successfully. Please check your email'));
                }else{
                    $request->session()->flash('error', trans('Can not send email. Please contact with admin.'));
                }
            }else{
                $request->session()->flash('error', trans('Your email not exist.'));
            }

            return redirect()->route('frontend.get.login.member');
        }

        return view('Auth::frontend.forgot_password');
    }
}
