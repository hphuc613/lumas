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
use Modules\Service\Model\ServiceType;
use Modules\Voucher\Http\Requests\VoucherRequest;
use Modules\Voucher\Model\Voucher;


class VoucherController extends Controller{

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
        $services = Service::query()->where('status', Status::STATUS_ACTIVE)->pluck('name', 'id')->toArray();
        $vouchers = Voucher::filter($filter)
                           ->where('status', Status::STATUS_ACTIVE)
                           ->orderBy('start_at', 'DESC')
                           ->paginate(20);
        return view("Voucher::index", compact('vouchers', 'services'));
    }

    /**
     * @return Application|Factory|View
     */
    public function getCreate(){
        $statuses      = Status::getStatuses();
        $service_types = ServiceType::query()->where('status', Status::STATUS_ACTIVE)->pluck('name', 'id')->toArray();
        return view("Voucher::create", compact('statuses', 'service_types'));
    }

    /**
     * @param VoucherRequest $request
     * @return RedirectResponse
     * @throws ErrorException
     */
    public function postCreate(VoucherRequest $request){
        $data             = $request->all();
        $data['start_at'] = formatDate($data['start_at'], 'Y-m-d');
        $data['end_at']   = (!empty($data['end_at'])) ? formatDate($data['end_at'], 'Y-m-d') : null;
        $voucher          = new Voucher($data);
        $voucher->save();

        $request->session()->flash('success', trans('Voucher created successfully.'));
        return redirect()->route('get.voucher.list');
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function getCreatePopUp(Request $request){
        $statuses      = Status::getStatuses();
        $service_types = ServiceType::query()->where('status', Status::STATUS_ACTIVE)->pluck('name', 'id')->toArray();

        if(!$request->ajax()){
            return redirect()->back();
        }

        return view("Voucher::_form", compact('statuses', 'service_types'));
    }

    /**
     * @param VoucherRequest $request
     * @throws ErrorException
     */
    public function postCreatePopUp(VoucherRequest $request){
        $this->postCreate($request);

        $request->session()->flash('success', trans('Voucher created successfully.'));
        return redirect()->back();
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function getUpdate($id){
        $statuses      = Status::getStatuses();
        $voucher       = Voucher::find($id);
        $service_types = ServiceType::query()->where('status', Status::STATUS_ACTIVE)->pluck('name', 'id')->toArray();
        $services      = Service::query()
                                ->where('type_id', $voucher->service->type_id)
                                ->where('status', Status::STATUS_ACTIVE)
                                ->pluck('name', 'id')
                                ->toArray();

        return view("Voucher::update", compact('statuses', 'voucher', 'services', 'service_types'));
    }

    /**
     * @param VoucherRequest $request
     * @return RedirectResponse
     * @throws ErrorException
     */
    public function postUpdate(VoucherRequest $request, $id){
        $data             = $request->all();
        $data['start_at'] = formatDate($data['start_at'], 'Y-m-d');
        $data['end_at']   = (!empty($data['end_at'])) ? formatDate($data['end_at'], 'Y-m-d') : null;
        $voucher          = Voucher::find($id);
        $voucher->update($data);
        $voucher->save();

        $request->session()->flash('success', trans('Voucher updated successfully.'));
        return redirect()->route('get.voucher.list');
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function delete(Request $request, $id){
        $voucher = Voucher::find($id);
        $voucher->delete();

        $request->session()->flash('success', trans('Voucher deleted successfully.'));
        return redirect()->route('get.voucher.list');
    }

    /**
     * @param $id
     * @return string
     */
    public function getListVoucherByService($id){
        $services = Voucher::where('status', Status::STATUS_ACTIVE)
                           ->where('service_id', $id)
                           ->orderBy('start_at', 'desc')
                           ->pluck('code', 'id')
                           ->toArray();
        $html     = "<option value=''>Select</option>";
        foreach($services as $key => $service){
            $html .= "<option value='$key'>$service</option>";
        }

        return $html;
    }
}
