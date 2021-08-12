<?php

namespace Modules\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Appointment\Model\Appointment;
use Modules\Base\Model\Status;
use Modules\Member\Model\Member;
use Modules\Member\Model\MemberService;
use Modules\Member\Model\MemberServiceHistory;
use Modules\Service\Model\Service;
use Modules\Voucher\Model\ServiceVoucher;


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
        $filter                = $request->all();
        $member                = Member::find($id);
        $services              = Service::getArray(Status::STATUS_ACTIVE);
        $query_member_services = new MemberService();

        /**
         * Get list service
         * @var  $member_services
         */
        $member_services = clone $query_member_services;
        $member_services = $member_services->filter($filter, $member->id)
                                           ->where('status', MemberService::COMPLETED_STATUS)
                                           ->paginate(5, ['*'], 'service_page');

        /**
         * Get list service completed
         * @var  $completed_member_services
         */
        $completed_member_services = clone $query_member_services;
        $completed_member_services = $completed_member_services->filterCompleted($filter, $member->id)
                                                               ->paginate(5, ['*'], 'service_page');

        /**
         * Get list using service
         * @var  $progressing_services
         */
        $progressing_services = clone $query_member_services;
        $progressing_services = $progressing_services->query()
                                                     ->where('member_id', $member->id)
                                                     ->where('status', MemberService::PROGRESSING_STATUS)
                                                     ->get();

        $search_services           = MemberService::getArrayByMember($member->id);
        $search_completed_services = MemberService::getArrayByMember($member->id, 1);
        $histories                 = MemberServiceHistory::filter($filter, $member->id)
                                                         ->paginate(10, ['*'], 'history_page');

        return view('Member::backend.member_service.index',
            compact('member', 'services', 'member_services', 'histories', 'completed_member_services', 'filter',
                'search_services', 'search_completed_services', 'progressing_services'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function getView(Request $request, $id){
        $filter                = $request->all();
        $query_member_services = new MemberService();


        $member_service = clone $query_member_services;
        $member_service = $member_service->find($id);

        /**
         * Get list service
         * @var  $member_services
         */
        $member_services = clone $query_member_services;
        $member_services = $member_services->filter($filter, $member_service->member_id)
                                           ->paginate(5, ['*'], 'service_page');

        /**
         * Get list service completed
         * @var  $completed_member_services
         */
        $completed_member_services = clone $query_member_services;
        $completed_member_services = $completed_member_services->filterCompleted($filter, $member_service->member_id)
                                                               ->paginate(5, ['*'], 'service_completed_page');

        $member   = Member::find($member_service->member_id);
        $services = Service::getArray();
        $vouchers = ServiceVoucher::query()->where('service_id', $member_service->service_id)
                                  ->pluck('code', 'id')->toArray();

        $histories = MemberServiceHistory::filter($filter, $member->id, $member_service->service_id)
                                         ->where('member_service_id', $member_service->id)
                                         ->paginate(10, ['*'], 'history_page');


        /**
         * Get list using service
         * @var  $progressing_services
         */
        $progressing_services = clone $query_member_services;
        $progressing_services = $progressing_services->query()
                                                     ->where('member_id', $member->id)
                                                     ->where('status', MemberService::PROGRESSING_STATUS)
                                                     ->get();

        $statuses                  = MemberService::getStatus();
        $search_services           = MemberService::getArrayByMember($member->id);
        $search_completed_services = MemberService::getArrayByMember($member->id, 1);
        return view('Member::backend.member_service.index',
            compact('member', 'services', 'member_services', 'member_service', 'vouchers', 'histories',
                'completed_member_services', 'filter', 'statuses', 'search_services', 'search_completed_services', 'progressing_services'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function delete(Request $request, $id){
        $member_service = MemberService::find($id);
        $member_id      = $member_service->member_id;
        $member_service->delete();

        $request->session()->flash('success', trans("Service deleted successfully."));
        return redirect()->route('get.member_service.add', $member_id);
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|RedirectResponse|string
     */
    public function eSign(Request $request, $id){
        $member_service = MemberService::find($id);


        if($request->post()){
            $data        = $request->all();
            $appointment = Appointment::query()
                                      ->where('member_id', $member_service->member_id)
                                      ->where('status', Appointment::PROGRESSING_STATUS)
                                      ->first();

            /** E-sign*/
            $member_service->eSign($data, $appointment);

            /**  Reduce the quantity of */
            $member_service->deduct_quantity += 1;
            $member_service->status          = MemberService::COMPLETED_STATUS;
            $member_service->save();

            $request->session()->flash('success', trans("Signed successfully."));
            return redirect()->back();
        }

        if(!$request->ajax()){
            return redirect()->back();
        }

        return $this->renderAjax('Member::backend.member_service.e_sign', compact('member_service'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function intoProgress(Request $request, $id){
        $member_service = MemberService::find($id);
        if(!$member_service->member->getAppointmentInProgressing()){
            $request->session()->flash('error', trans("Please check in an appointment."));

            return redirect()->back();
        }
        $member_service->status     = MemberService::PROGRESSING_STATUS;
        $member_service->updated_by = Auth::id();
        $member_service->save();
        $request->session()->flash('success', trans("Client using this service."));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function outProgress(Request $request, $id){
        $member_service         = MemberService::find($id);
        $member_service->status = MemberService::COMPLETED_STATUS;
        $member_service->save();
        $request->session()->flash('success', trans("Service has been removed from Service progressing list."));

        return redirect()->back();
    }
}
