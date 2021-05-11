<?php

namespace Modules\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
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
            $data = $request->all();
            if(empty($request->password)){
                unset($data['password']);
            }
            $member->update($data);
            $request->session()->flash('success', trans("Updated successfully"));
        }
        return redirect()->back();
    }
}
