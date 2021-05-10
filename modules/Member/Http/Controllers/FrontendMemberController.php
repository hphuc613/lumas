<?php

namespace Modules\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Member\Model\Member;


class FrontendMemberController extends Controller{


    public function __construct(){
        # parent::__construct();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile(Request $request){
        $member = Member::find(Auth::guard('member')->user()->id);
        if($request->post()){
            $data = $request->post();
            if(empty($request->password)){
                unset($data['password']);
            }
            $member->update($data);
            $request->session()->flash('success', trans("Updated successfully"));

            return redirect()->back();
        }

        return view("Member::frontend.profile", compact('member'));
    }
}
