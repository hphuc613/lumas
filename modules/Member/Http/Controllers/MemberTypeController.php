<?php

namespace Modules\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Base\Model\Status;
use Modules\Member\Http\Requests\MemberTypeRequest;
use Modules\Member\Model\MemberType;
use Modules\Service\Http\Requests\ServiceTypeRequest;


class MemberTypeController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }


    public function index(Request $request){
        $filter       = $request->all();
        $member_types = MemberType::filter($filter)->paginate(50);

        return view('Member::backend.member_type.index', compact('member_types', 'filter'));
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function getCreate(Request $request){
        $statuses = Status::getStatuses();

        if(!$request->ajax()){
            return redirect()->back();
        }

        return $this->renderAjax('Member::backend.member_type.form', compact('statuses'));
    }

    /**
     * @param MemberTypeRequest $request
     * @return RedirectResponse
     */
    public function postCreate(MemberTypeRequest $request){
        $member_type = new MemberType($request->all());
        $member_type->save();
        $request->session()->flash('success', trans('Client type created successfully.'));

        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|RedirectResponse|string
     */
    public function getUpdate(Request $request, $id){
        $member_type = MemberType::find($id);
        $statuses    = Status::getStatuses();
        if(!$request->ajax()){
            return redirect()->back();
        }
        return $this->renderAjax('Member::backend.member_type.form', compact('member_type', 'statuses'));
    }

    /**
     * @param ServiceTypeRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function postUpdate(MemberTypeRequest $request, $id){
        $member_type = MemberType::find($id);
        $member_type->update($request->all());
        $request->session()->flash('success', trans('Client type updated successfully.'));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function delete(Request $request, $id){
        $member_type = MemberType::find($id);
        if($member_type->members->isEmpty()){
            $member_type->delete();
            $request->session()->flash('success', trans('Client type deleted successfully.'));
        }else{
            $request->session()->flash('error',
                                       trans('This client type cannot delete because has active clients belongs this type'));
        }

        return back();
    }
}
