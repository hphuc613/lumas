<?php

namespace Modules\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use ErrorException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Base\Model\Status;
use Modules\Member\Http\Requests\MemberServiceRequest;
use Modules\Member\Model\Member;
use Modules\Member\Model\MemberService;
use Modules\Member\Model\MemberServiceHistory;
use Modules\Service\Model\Service;
use Modules\Voucher\Model\Voucher;


class MemberServiceController extends Controller{

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
     * @param $id
     * @return Application|Factory|View
     */
    public function getAdd(Request $request, $id){
        $member          = Member::find($id);
        $services        = Service::getArray(Status::STATUS_ACTIVE);
        $member_services = MemberService::filter($request->all(), $member->id)
                                        ->paginate(5, ['*'], 'service_page');
        $histories       = MemberServiceHistory::filter($request->all(), $member->id)
                                               ->paginate(10, ['*'], 'history_page');

        return view('Member::backend.member_service.index', compact('member', 'services', 'member_services', 'histories'));
    }

    /**
     * @param MemberServiceRequest $request
     * @param $id
     * @return RedirectResponse
     * @throws ErrorException
     */
    public function postAdd(MemberServiceRequest $request, $id){
        $data                       = $request->all();
        $member                     = Member::find($id);
        $service                    = Service::where('id', $data['service_id'])->first();
        $member_service_check_exist = MemberService::query()
                                                   ->where('service_id', $service->id)
                                                   ->where('member_id', $member->id)
                                                   ->where('voucher_id', $request->voucher_id)
                                                   ->first();
        if(!empty($member_service_check_exist)){
            $request->session()->flash('error', "This service has not been used yet. Update right here.");
            return redirect()->route("get.member_service.edit", $member_service_check_exist->id);
        }

        $member_service       = new MemberService($data);
        $member_service->code = $member_service->generateCode();
        $member_service->save();
        $request->session()->flash('success', "Service added successfully.");
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function getEdit(Request $request, $id){
        $member_service  = MemberService::find($id);
        $member_services = MemberService::filter($request->all(), $member_service->member_id)
                                        ->paginate(5, ['*'], 'service_page');
        $member          = Member::find($member_service->member_id);
        $services        = Service::getArray(Status::STATUS_ACTIVE);
        $vouchers        = Voucher::query()
                                  ->where('service_id', $member_service->service_id)
                                  ->where('status', Status::STATUS_ACTIVE)
                                  ->pluck('code', 'id')->toArray();

        $histories = MemberServiceHistory::filter($request->all(), $member->id, $member_service->service_id)
                                         ->paginate(10, ['*'], 'history_page');

        return view('Member::backend.member_service.index', compact(
            'member', 'services', 'member_services', 'member_service', 'vouchers', 'histories'));
    }

    /**
     * @param MemberServiceRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function postEdit(MemberServiceRequest $request, $id){
        $member_service = MemberService::find($id);
        $member_service->update($request->all());

        $request->session()->flash('success', "Service edited successfully.");
        return redirect()->back();
    }

    /**
     * @param MemberServiceRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function delete(Request $request, $id){
        $member_service = MemberService::find($id);
        $member_service->delete();

        $request->session()->flash('success', "Service deleted successfully.");
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|RedirectResponse|string
     */
    public function eSign(Request $request, $id){
        $member_service = MemberService::find($id);

        if($request->post()){
            $data = $request->all();
            if(empty($data['signature'])){
                $data['signature'] = Auth::user()->name;
            }
            $data['member_service_id'] = $id;
            $data['updated_by']        = Auth::id();
            $history                   = new MemberServiceHistory($data);
            $history->save();

            /**  Reduce the quantity of */
            $member_service->deduct_quantity += 1;
            $member_service->save();

            $request->session()->flash('success', "Signed successfully.");
            return redirect()->back();
        }

        if(!$request->ajax()){
            return redirect()->back();
        }

        return $this->renderAjax('Member::backend.member_service.e_sign', compact('member_service'));
    }
}
