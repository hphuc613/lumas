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
use Modules\Course\Model\Course;
use Modules\Service\Model\Service;
use Modules\Voucher\Http\Requests\VoucherRequest;
use Modules\Voucher\Model\Voucher;


class VoucherController extends Controller {

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        # parent::__construct();
    }

    public function index(Request $request) {
        $filter   = $request->all();
        $services = Service::query()->where('status', Status::STATUS_ACTIVE)->pluck('name', 'id')->toArray();
        $vouchers = Voucher::filter($filter)
                           ->where('status', Status::STATUS_ACTIVE)
                           ->orderBy('start_at', 'DESC')
                           ->paginate(15);
        $types    = Voucher::getTypeList();
        return view("Voucher::index", compact('vouchers', 'services', 'types', 'filter'));
    }

    /**
     * @return Application|Factory|View
     */
    public function getCreate() {
        $statuses = Status::getStatuses();
        $types    = Voucher::getTypeList();
        $services = Service::getArray(Status::STATUS_ACTIVE);
        $courses  = Course::getArray(Status::STATUS_ACTIVE);
        return view("Voucher::create", compact('statuses', 'services', 'courses', 'types'));
    }

    /**
     * @param VoucherRequest $request
     * @return RedirectResponse
     * @throws ErrorException
     */
    public function postCreate(VoucherRequest $request) {
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
    public function getCreatePopUp(Request $request) {
        $statuses = Status::getStatuses();
        $types    = Voucher::getTypeList();
        $services = Service::getArray(Status::STATUS_ACTIVE);
        $courses  = Course::getArray(Status::STATUS_ACTIVE);

        if (!$request->ajax()) {
            return redirect()->back();
        }

        return view("Voucher::_form", compact('statuses', 'services', 'courses', 'types'));
    }

    /**
     * @param VoucherRequest $request
     * @throws ErrorException
     */
    public function postCreatePopUp(VoucherRequest $request) {
        $this->postCreate($request);

        $request->session()->flash('success', trans('Voucher created successfully.'));
        return redirect()->back();
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function getUpdate($id) {
        $statuses = Status::getStatuses();
        $voucher  = Voucher::find($id);
        $types    = Voucher::getTypeList();
        $services = Service::getArray(Status::STATUS_ACTIVE);
        $courses  = Course::getArray(Status::STATUS_ACTIVE);

        return view("Voucher::update", compact('statuses', 'voucher', 'services', 'courses', 'types'));
    }

    /**
     * @param VoucherRequest $request
     * @return RedirectResponse
     * @throws ErrorException
     */
    public function postUpdate(VoucherRequest $request, $id) {
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
    public function delete(Request $request, $id) {
        $voucher = Voucher::find($id);
        $voucher->delete();

        $request->session()->flash('success', trans('Voucher deleted successfully.'));
        return redirect()->route('get.voucher.list');
    }

    /**
     * @param $id
     * @param $type
     * @return string
     */
    public function getListVoucherByParentID($id, $type) {
        $services = Voucher::where('status', Status::STATUS_ACTIVE)
                           ->where('parent_id', $id)
                           ->where('type', $type)
                           ->orderBy('start_at', 'desc')->pluck('code', 'id')->toArray();
        $html     = "<option value=''>Select</option>";
        foreach ($services as $key => $service) {
            $html .= "<option value='$key'>$service</option>";
        }

        return $html;
    }
}
