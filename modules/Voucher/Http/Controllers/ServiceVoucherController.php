<?php

namespace Modules\Voucher\Http\Controllers;

use App\Http\Controllers\Controller;
use ErrorException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Base\Model\Status;
use Modules\Service\Model\Service;
use Modules\Voucher\Http\Requests\ServiceVoucherRequest;
use Modules\Voucher\Model\ServiceVoucher;


class ServiceVoucherController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    public function index(Request $request){
        $filter   = $request->all();
        $services = Service::getArray(Status::STATUS_ACTIVE);
        $vouchers = ServiceVoucher::filter($filter)
                                  ->where('status', Status::STATUS_ACTIVE)
                                  ->WhereHas('service', function($qs){
                                      $qs->where('status', Status::STATUS_ACTIVE);
                                  })
                                  ->orderBy('start_at', 'DESC')
                                  ->paginate(15);
        return view("Voucher::service_voucher.index", compact('vouchers', 'services', 'filter'));
    }

    /**
     * @return Application|Factory|View
     */
    public function getCreate(){
        $statuses = Status::getStatuses();
        $services = Service::getArray(Status::STATUS_ACTIVE);
        return view("Voucher::service_voucher.create", compact('statuses', 'services'));
    }

    /**
     * @param ServiceVoucherRequest $request
     * @return RedirectResponse
     * @throws ErrorException
     */
    public function postCreate(ServiceVoucherRequest $request){
        $data             = $request->all();
        $data['start_at'] = formatDate(strtotime($data['start_at']), 'Y-m-d H:i:s');
        $data['end_at']   = (!empty($data['end_at'])) ? formatDate($data['end_at'], 'Y-m-d') : null;
        $voucher          = new ServiceVoucher($data);
        $voucher->save();

        $request->session()->flash('success', trans('Voucher created successfully.'));
        return redirect()->route('get.service_voucher.list');
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function getCreatePopUp(Request $request){
        $statuses = Status::getStatuses();
        $services = Service::getArray(Status::STATUS_ACTIVE);

        if(!$request->ajax()){
            return redirect()->back();
        }

        return view("Voucher::service_voucher._form", compact('statuses', 'services'));
    }

    /**
     * @param ServiceVoucherRequest $request
     * @throws ErrorException
     */
    public function postCreatePopUp(ServiceVoucherRequest $request){
        $this->postCreate($request);

        $request->session()->flash('success', trans('Voucher created successfully.'));
        return redirect()->back();
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function getUpdate($id){
        $statuses = Status::getStatuses();
        $voucher  = ServiceVoucher::find($id);
        $services = Service::getArray(Status::STATUS_ACTIVE);

        return view("Voucher::service_voucher.update", compact('statuses', 'voucher', 'services'));
    }

    /**
     * @param ServiceVoucherRequest $request
     * @return RedirectResponse
     * @throws ErrorException
     */
    public function postUpdate(ServiceVoucherRequest $request, $id){
        $data             = $request->all();
        $data['start_at'] = formatDate(strtotime($data['start_at']), 'Y-m-d H:i:s');
        $data['end_at']   = (!empty($data['end_at'])) ? formatDate($data['end_at'], 'Y-m-d') : null;
        $voucher          = ServiceVoucher::find($id);
        $voucher->update($data);
        $voucher->save();

        $request->session()->flash('success', trans('Voucher updated successfully.'));
        return redirect()->route('get.service_voucher.list');
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function delete(Request $request, $id){
        $voucher = ServiceVoucher::find($id);
        $voucher->delete();

        $request->session()->flash('success', trans('Voucher deleted successfully.'));
        return redirect()->route('get.service_voucher.list');
    }

    /**
     * @param $id
     * @param $type
     * @return string
     */
    public function getListVoucherByServiceID($id){
        $services = ServiceVoucher::where('status', Status::STATUS_ACTIVE)
                                  ->where('service_id', $id)
                                  ->orderBy('start_at', 'desc')->pluck('code', 'id')->toArray();
        $html     = "<option value=''>Select</option>";
        foreach($services as $key => $service){
            $html .= "<option value='$key'>$service</option>";
        }

        return $html;
    }
}
