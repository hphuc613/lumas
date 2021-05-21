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
        $vouchers = Voucher::filter($filter)
                           ->where('status', Status::STATUS_ACTIVE)
                           ->orderBy('start_at', 'DESC')
                           ->paginate(20);
        return view("Voucher::index", compact('vouchers'));
    }

    /**
     * @return Application|Factory|View
     */
    public function getCreate(){
        $statuses = Status::STATUSES;

        return view("Voucher::create", compact('statuses'));
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
     * @return Application|Factory|View
     */
    public function getUpdate($id){
        $statuses = Status::STATUSES;
        $voucher  = Voucher::find($id);
        return view("Voucher::update", compact('statuses', 'voucher'));
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
}