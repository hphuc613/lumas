<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Base\Model\Status;
use Modules\Course\Model\Course;
use Modules\Member\Http\Requests\MemberCourseRequest;
use Modules\Member\Http\Requests\MemberServiceRequest;
use Modules\Member\Model\Member;
use Modules\Member\Model\MemberCourse;
use Modules\Member\Model\MemberService;
use Modules\Order\Model\Order;
use Modules\Order\Model\OrderDetail;
use Modules\PaymentMethod\Model\PaymentMethod;
use Modules\Service\Model\Service;
use Modules\User\Model\User;
use Modules\Voucher\Model\CourseVoucher;
use Modules\Voucher\Model\ServiceVoucher;
use Throwable;


class OrderController extends Controller{

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
        $filter      = $request->all();
        $orders      = Order::filter($filter)->orderBy('updated_at', 'desc')->paginate(15);
        $statuses    = Order::getStatus();
        $members     = Member::getArray();
        $creators    = User::query()->pluck('name', 'id')->toArray();
        $order_types = [
            Order::SERVICE_TYPE => trans('Service'),
            Order::COURSE_TYPE  => trans('Course')
        ];
        return view("Order::index", compact('orders', 'statuses', 'filter', 'members', 'order_types', 'creators'));
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function orderDetail($id){
        $order = Order::query()->find($id);
        return view("Order::detail", compact('order'));
    }


    /**
     * @param Request $request
     * @param $order_type
     * @param $id
     * @return array|RedirectResponse|string
     */
    public function getAddToCart(Request $request, $order_type, $id){
        if (!$request->ajax()) {
            return redirect()->back();
        }

        $payment_methods = PaymentMethod::getArray(Status::STATUS_ACTIVE);

        $order = Order::with('orderDetails')
                      ->with('member')
                      ->where('member_id', $id)
                      ->where('status', Order::STATUS_DRAFT)
                      ->where('order_type', $order_type)
                      ->first();

        if ($order_type === Order::COURSE_TYPE) {
            return $this->renderAjax('Order::cart_course', compact('order', 'payment_methods'));
        }
        return $this->renderAjax('Order::cart', compact('order', 'payment_methods'));
    }

    /**
     * @param MemberServiceRequest $request
     * @return string
     */
    public function postAddOrderService(MemberServiceRequest $request){
        $data   = $request->all();
        $member = Member::find($data['member_id']);
        $order  = $member->getDraftOrder($data['order_type']);

        DB::beginTransaction();

        try {
            if (empty($order)) {
                $order              = new Order();
                $order->member_id   = $data['member_id'];
                $order->code        = $order->generateCode();
                $order->remarks     = $data['remarks'];
                $order->total_price = 0;
                $order->status      = Order::STATUS_DRAFT;
                $order->order_type  = $data['order_type'];
                $order->created_by  = Auth::id();
                $order->updated_by  = Auth::id();
            }

            $order->updated_by = Auth::id();
            $order->save();

            $service       = Service::query()->find($data['service_id']);
            $data['price'] = 0;
            if (!empty($data['voucher_id'])) {
                $voucher       = ServiceVoucher::query()->find($data['voucher_id']);
                $data['price'] = (int)$voucher->price;

            } else {
                $data['price'] = (int)$service->price;
            }
            $data['price'] = $data['price'] - ($data['price'] * ((int)$data['discount']/100));
            $order_detail = OrderDetail::query()
                                       ->where('order_id', $order->id)
                                       ->where('product_id', $data['service_id'])
                                       ->where('voucher_id', $data['voucher_id'])
                                       ->first();

            if (empty($order_detail)) {
                $order_detail                = new OrderDetail();
                $order_detail->order_id      = $order->id;
                $order_detail->product_id    = $data['service_id'];
                $order_detail->product_price = $service->price;
                $order_detail->voucher_id    = $data['voucher_id'];
                $order_detail->voucher_price = $voucher->price ?? 0;
                $order_detail->discount      = (int)$data['discount'];
                $order_detail->price         = $data['price'];
                $order_detail->quantity      = (int)$data['quantity'];
            } else {
                $order_detail->quantity = $order_detail->quantity + (int)$data['quantity'];
            }

            $order_detail->amount = $order_detail->price * $order_detail->quantity;
            $order_detail->save();

            DB::commit();
            $request->session()->flash('success', trans("Service added to invoice successfully."));
            return redirect()->back();
        } catch(Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();

            $request->session()->flash('danger', trans("Service added to invoice failed. Please try again."));
            return redirect()->back();
        }
    }

    /**
     * @param MemberCourseRequest $request
     * @return string
     */
    public function postAddOrderCourse(MemberCourseRequest $request){
        $data   = $request->all();
        $member = Member::find($data['member_id']);
        $order  = $member->getDraftOrder($data['order_type']);

        DB::beginTransaction();

        try {
            if (empty($order)) {
                $order              = new Order();
                $order->member_id   = $data['member_id'];
                $order->code        = $order->generateCode();
                $order->remarks     = $data['remarks'];
                $order->total_price = 0;
                $order->status      = Order::STATUS_DRAFT;
                $order->order_type  = $data['order_type'];
                $order->created_by  = Auth::id();
                $order->updated_by  = Auth::id();
            }

            $order->updated_by = Auth::id();
            $order->save();

            $course        = Course::query()->find($data['course_id']);
            $data['price'] = 0;

            if (!empty($data['voucher_id'])) {
                $voucher       = CourseVoucher::query()->find($data['voucher_id']);
                $data['price'] = $voucher->price;

            } else {
                $data['price'] = $course->price;
            }


            $order_detail = OrderDetail::query()
                                       ->where('order_id', $order->id)
                                       ->where('product_id', $data['course_id'])
                                       ->where('voucher_id', $data['voucher_id'])
                                       ->first();

            if (empty($order_detail)) {
                $order_detail                  = new OrderDetail();
                $order_detail['order_id']      = $order->id;
                $order_detail['product_id']    = $data['course_id'];
                $order_detail['product_price'] = $course->price;
                $order_detail['voucher_id']    = $data['voucher_id'];
                $order_detail['voucher_price'] = $voucher->price ?? 0;
                $order_detail['price']         = $data['price'];
                $order_detail['quantity']      = (int)$data['quantity'];
            } else {
                $order_detail['quantity'] = $order_detail->quantity + (int)$data['quantity'];
            }

            $order_detail['amount'] = $order_detail['price'] * $order_detail['quantity'];
            $order_detail->save();

            DB::commit();
            $request->session()->flash('success', trans("Course added to invoice successfully."));
            return redirect()->back();
        } catch(Throwable $th) {
            DB::rollBack();
            $request->session()->flash('danger', trans("Course added to invoice failed. Please try again."));
            return redirect()->back();
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function abort(Request $request, $id){
        $order         = Order::query()->find($id);
        $order->status = Order::STATUS_ABORT;
        $order->save();

        $request->session()->flash('success', trans("Aborted successfully."));
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function purchase(Request $request, $id){
        $order = Order::query()->find($id);

        if ($order->status !== Order::STATUS_DRAFT) {
            $request->session()->flash('danger', trans("Can not purchase this invoice. Please check again"));
            return redirect()->back();
        }
        $data = $request->product;
        if (empty($data)) {
            $request->session()->flash('danger', trans("This invoice is empty"));
            return redirect()->back();
        }
        if (empty($request->payment_method_id)) {
            $request->session()->flash('danger', trans("Please select Payment method"));
            return redirect()->back();
        }

        foreach($data as $key => $value) {
            if ($value['quantity'] < 1) {
                continue;
            }

            $order_detail           = OrderDetail::find($key);
            $order_detail->quantity = $value['quantity'];
            $order_detail->amount   = $order_detail->price * $order_detail->quantity;
            $order_detail->save();

            $value['discount'] = $order_detail->discount;

            if ($order->order_type === Order::COURSE_TYPE) {
                MemberCourse::insertData($value);
            } else {
                MemberService::insertData($value);
            }
        }

        $order->total_price       = $order->orderDetails->sum('amount');
        $order->payment_method_id = $request->payment_method_id ?? null;
        $order->status            = Order::STATUS_PAID;
        $order->save();

        $request->session()->flash('success', trans("Paid successfully."));
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function deleteItem(Request $request, $id){
        $order_details = OrderDetail::query()->find($id);
        $order_details->delete();

        $request->session()->flash('success', trans("Deleted successfully."));
        return redirect()->back();
    }

}
