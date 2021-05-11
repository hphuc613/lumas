<?php

namespace Modules\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Base\Model\Status;
use Modules\Member\Http\Requests\MemberRequest;
use Modules\Member\Model\Member;


class MemberController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request){
        $members = Member::where('deleted_at', null)->paginate(20);
        return view("Member::backend.index", compact('members'));
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function getUpdate($id){
        $member   = Member::find($id);
        $statuses = Status::STATUSES;
        return view('Member::backend.update', compact('member', 'statuses'));
    }

    /**
     * @param MemberRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function postUpdate(MemberRequest $request, $id){
        if($request->post()){
            $data   = $request->all();
            $member = Member::find($id);
            if(empty($data['password'])){
                unset($data['password']);
            }
            unset($data['password_re_enter']);
            $member->update($data);
            $request->session()->flash('success', trans('Member updated successfully.'));
        }

        return redirect()->route('get.member.list');
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function postUpdateStatus(Request $request){
        $data = $request->all();
        if($data != null){
            $member = Member::find($data['id']);
            if($member){
                $member->status = $data['status'];
                $member->save();
                $request->session()->flash('success', trans('Member updated successfully.'));
            }
        }
        return true;
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function delete(Request $request, $id){
        $member = Member::find($id);
        $member->delete();
        $request->session()->flash('success', trans('Member updated successfully.'));

        return redirect()->back();
    }
}
