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
            $contact_info = [];
            if(!empty($member->contact_info)){
                $contact_info = json_decode($member->contact_info, 1);
            }
            $data                 = $request->all();
            $data['contact_info'] = json_encode(array_merge($contact_info, $data['contact_info']));
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
     * @return array|RedirectResponse|string
     */
    public function getChangeAvatar(Request $request){
        if(!$request->ajax()){
            return redirect()->back();
        }
        return $this->renderAjax('Member::frontend.change_avatar');
    }

    /**
     * @param MemberRequest $request
     * @return array|string
     */
    public function postChangeAvatar(MemberRequest $request){
        if($request->hasFile('contact_info')){
            $image  = $request->contact_info['avatar'];
            $member = Member::find(Auth::guard('member')->id());

            $upload_folder = 'upload/member/' . $member->id . '-' . $member->username . '/avatar';
            $image_name    = $member->username . '.' . $image->getClientOriginalExtension();

            $contact_info           = json_decode($member->contact_info, 1);
            $contact_info['avatar'] = $upload_folder . '/' . $image_name;
            $member->contact_info   = json_encode($contact_info);
            $member->save();

            $image->storeAs('public/' . $upload_folder, $image_name);

            $request->session()->flash('success', trans("Avatar Updated successfully"));

            return redirect()->back();
        }

        return redirect()->back();
    }
}
