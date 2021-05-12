<?php

namespace Modules\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Member\Http\Requests\MemberRequest;
use Modules\Member\Model\Member;

/**
 * Class FrontendMemberController
 * @package Modules\Member\Http\Controllers
 */
class FrontendMemberController extends Controller{

    /**
     * @return Application|Factory|RedirectResponse|View
     */
    public function getProfile(){
        $member = Member::where('id', Auth::guard('member')->user()->id)
                        ->where('deleted_at', null)
                        ->first();
        if(!empty($member)){
            $member->contact_info = json_decode($member->contact_info);
            return view("Member::frontend.profile", compact('member'));
        }

        return redirect()->route('frontend.get.login.member');
    }

    /**
     * @param MemberRequest $request
     * @return RedirectResponse
     */
    public function postProfile(MemberRequest $request){
        $member = Member::where('id', Auth::guard('member')->user()->id)->where('deleted_at', null)->first();
        if($request->post() && !empty('member')){
            $data                 = $request->all();
            $data['contact_info'] = json_encode($data['contact_info']);
            if(empty($request->password)){
                unset($data['password']);
            }
            $member->update($data);
            $request->session()->flash('success', trans("Updated successfully"));
        }
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function getChangeAvatar(Request $request){

        return $this->renderAjax('Member::frontend.change_avatar');
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function postChangeAvatar(Request $request){
        if(!$request->ajax()){
            return redirect()->back();
        }

        return $this->renderAjax('Member::frontend.change_avatar');
    }
}
