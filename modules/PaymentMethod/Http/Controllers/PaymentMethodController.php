<?php

namespace Modules\PaymentMethod\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Base\Model\Status;
use Modules\PaymentMethod\Http\Requests\PaymentMethodRequest;
use Modules\PaymentMethod\Model\PaymentMethod;


class PaymentMethodController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    public function index(Request $request){
        $statuses        = Status::getStatuses();
        $payment_methods = PaymentMethod::query()->paginate(20);
        return view("PaymentMethod::index", compact('payment_methods', 'statuses'));
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function getCreate(Request $request){
        $statuses = Status::getStatuses();

        if (!$request->ajax()) {
            return redirect()->back();
        }

        return $this->renderAjax('PaymentMethod::form', compact('statuses'));
    }

    /**
     * @param PaymentMethodRequest $request
     * @return RedirectResponse
     */
    public function postCreate(PaymentMethodRequest $request){
        $payment_method = new PaymentMethod($request->all());
        $payment_method->save();
        $request->session()->flash('success', trans('Payment Method created successfully.'));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function getUpdate(Request $request, $id){
        $payment_method = PaymentMethod::find($id);
        $statuses       = Status::getStatuses();

        if (!$request->ajax()) {
            return redirect()->back();
        }

        return $this->renderAjax('PaymentMethod::form', compact('statuses', 'payment_method'));
    }

    /**
     * @param PaymentMethodRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function postUpdate(PaymentMethodRequest $request, $id){
        $instrument = PaymentMethod::query()->find($id);
        $instrument->update($request->all());
        $request->session()->flash('success', trans('Payment Method updated successfully.'));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws \Exception
     */
    public function delete(Request $request, $id){
        $instrument = PaymentMethod::query()->find($id);
        $instrument->delete();
        $request->session()->flash('success', trans('Payment Method deleted successfully.'));

        return back();
    }
}
