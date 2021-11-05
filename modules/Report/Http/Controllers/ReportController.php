<?php

namespace Modules\Report\Http\Controllers;

use App\AppHelpers\Excel\Export;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Base\Model\Status;
use Modules\Member\Model\Member;
use Modules\Member\Model\MemberService;
use Modules\Member\Model\MemberServiceHistory;
use Modules\Order\Model\Order;
use Modules\Role\Model\Role;
use Modules\Service\Model\Service;
use Modules\Store\Model\Store;
use Modules\User\Model\User;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class ReportController extends Controller{

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
     * @return Factory|View|BinaryFileResponse
     */
    public function service(Request $request){
        $filter = $request->all();
        $users  = User::with('roles')
                      ->whereHas('roles', function($role_query){
                          $admin = Role::query()->where('name', Role::ADMINISTRATOR)->first();
                          return $role_query->whereNotIn('role_id', [$admin->id]);
                      })
                      ->where('status', Status::STATUS_ACTIVE)
                      ->pluck('name', 'id')->toArray();

        $services = Service::getArray(Status::STATUS_ACTIVE);


        $member_services = MemberService::query()
                                        ->with('service')
                                        ->with(['histories' => function($sh) use ($request){
                                            $sh->with('user');
                                            if (isset($request->user_id)) {
                                                $sh->where('updated_by', $request->user_id);
                                            }
                                            if (isset($request->from)) {
                                                $sh->where('end', '>=', formatDate(strtotime($request->from), 'Y-m-d'));
                                            }
                                            if (isset($request->to)) {
                                                $sh->where('end', '<=', formatDate(strtotime($request->to) +
                                                                                   86400, 'Y-m-d'));
                                            }
                                        }])->has('histories');

        if (isset($request->service_id)) {
            $member_services = $member_services->where('service_id', $request->service_id);
        }
        $member_services
            = $member_services->join('member_service_histories', 'member_services.id', '=', 'member_service_id');
        if (isset($filter['sort']) && $filter['sort'] == 'asc') {
            $member_services = $member_services->orderBy('member_service_histories.end');
        } else {
            $member_services = $member_services->orderBy('member_service_histories.end', 'desc');
        }
        $member_services = $member_services->get();

        $data   = [];
        $i      = 1;
        $staffs = [];
        foreach($member_services as $item) {
            $check_key_data = '';
            foreach($item->histories as $history) {
                $count_item = 1;
                $date       = formatDate($history->end, 'd-m-Y');
                $user_name  = $history->user->name ?? "N/A";
                if ($check_key_data == $date . '-' . $user_name . '-' . $item->code) {
                    $count_item = $count_item + 1;
                }
                $key_data                        = $date . '-' . $user_name . '-' . $item->code;
                $data[$key_data]['id']           = $i;
                $data[$key_data]['date']         = $date;
                $data[$key_data]['code']         = $item->code;
                $data[$key_data]['price']        = $item->price;
                $data[$key_data]['user_name']    = $user_name ?? "N/A";
                $data[$key_data]['service_name'] = $item->service->name ?? "N/A";
                $data[$key_data]['signature']    = $history->signature;
                $data[$key_data]['quantity']     = $count_item;
                $data[$key_data]['amount']       = $count_item * $item->price;
                $check_key_data                  = $key_data;
                $i++;

                $staffs[$user_name][] = $data;
            }
        }

        if (isset($request->export)) {
            $data_export = [];
            foreach($data as $key => $val) {
                $data_export[$key]['date']         = $val['date'];
                $data_export[$key]['code']         = $val['code'];
                $data_export[$key]['service_name'] = $val['service_name'];
                $data_export[$key]['user_name']    = $val['user_name'];
                $data_export[$key]['quantity']     = $val['quantity'] . ' ' . trans('Times');
                $data_export[$key]['amount']       = moneyFormat($val['amount']);
                $data_export[$key]['signature']    = $val['signature'];
            }

            $export             = new Export;
            $export->collection = collect($data_export);
            $export->headings   = [trans('Date'), trans('Code'), trans('Service'), trans('Staff'), trans('Times'),
                                   trans('Amount'), trans('Signature')];
            return Excel::download($export, 'service_information.xlsx');
        }

        $data = $this->paginate($data, 50);

        return view("Report::service", compact('data', 'users', 'services', 'filter', 'staffs'));
    }

    /**
     * @param $id
     * @return BinaryFileResponse
     */
    public function exportTreatmentClient($id){
        $member_services = MemberService::query()->where('member_id', $id)
                                        ->where('status', MemberService::COMPLETED_STATUS)
                                        ->get();
        $data_export     = [];
        foreach($member_services as $key => $value) {
            $data_export[$key]['code']        = $value->code;
            $data_export[$key]['service']     = $value->service->name ?? "N/A";
            $data_export[$key]['voucher']     = $value->voucher->code ?? "N/A";
            $data_export[$key]['remaining']   = $value->getRemaining();
            $data_export[$key]['quantity']    = $value->quantity;
            $data_export[$key]['price']       = moneyFormat($value->price, 0);
            $data_export[$key]['total_price'] = moneyFormat($value->price * $value->quantity, 0);
            $data_export[$key]['created_at']  = formatDate(strtotime($value->created_at), 'd-m-Y H:i');
        }

        $export             = new Export;
        $export->collection = collect($data_export);
        $export->headings   = [trans('Code'), trans('Service'), trans('Voucher'), trans('Remaining'),
                               trans('Quantity'), trans('Price'), trans('Total Price'), trans('Created At')];

        return Excel::download($export, 'treatment_information.xlsx');
    }

    /**
     * @param Request $request
     * @return Factory|View|BinaryFileResponse
     */
    public function sale(Request $request){
        $filter      = $request->all();
        $statuses    = Order::getStatus();
        $members     = Member::getArray();
        $creators    = User::query()->pluck('name', 'id')->toArray();
        $order_types = [
            Order::SERVICE_TYPE => trans('Service'),
            Order::COURSE_TYPE  => trans('Course')
        ];
        $orders      = Order::filter($filter)
                            ->join('users', 'users.id', '=', 'created_by')
                            ->select('users.name', 'orders.*', DB::raw("DATE_FORMAT(orders.created_at,'%d-%m-%Y') as date"))
                            ->orderBy('date', 'desc')
                            ->orderBy('users.name', 'asc');
        if (!isset($filter['month'])) {
            $orders = $orders->whereMonth('orders.updated_at', formatDate(time(), 'm'));
        }

        if (isset($request->export)) {
            $orders_export = clone $orders;
            $data          = [];
            $data_export   = [];
            $i             = 0;
            foreach($orders_export->get() as $key => $order) {
                $data[$key]['creator']       = $order->creator->name ??
                                               $order->name . " " . trans('This data has been deleted') ?? "N/A";
                $data[$key]['code']          = (is_numeric($order->code)) ? 'CWB' . $order->code : $order->code;
                $data[$key]['status']        = $statuses[$order->status];
                $data[$key]['id_number']     = empty($order->member->id_number) ? null :
                    "CWB" . $order->member->id_number;
                $data[$key]['name']          = isset($order->member->id) ? $order->member->name : "N/A";
                $data[$key]['updated_at']    = formatDate(strtotime($order->updated_at), 'd-m-Y H:i');
                $data[$key]['paymentMethod'] = $order->paymentMethod->name ?? NULL;

                foreach($order->orderDetails as $detail) {
                    $data_export[$i]                = $data[$key];
                    $data_export[$i]['product']     = $detail->product->name;
                    $data_export[$i]['voucher']     = $detail->productVoucher->code ?? NULL;
                    $data_export[$i]['cost']        = empty($detail->voucher_id) ? $detail->product->price :
                        $detail->productVoucher->price;
                    $data_export[$i]['discount']    = $detail->discount . "%";
                    $data_export[$i]['price']       = $detail->price;
                    $data_export[$i]['quantity']    = $detail->quantity;
                    $data_export[$i]['total_price'] = $detail->amount;
                    $data_export[$i]['amount']      = $order->total_price;
                    $i++;
                }

            }

            $export             = new Export;
            $export->collection = collect($data_export);
            $export->headings   = [trans('Order Creator'), trans('Invoice Code'), trans('Status'),
                                   trans('Client ID'), trans('Client Name'),
                                   trans('Purchase/Abort At'), trans('Payment Method'),
                                   trans('Product'), trans('Voucher'),
                                   trans('Cost'), trans('Discount'),
                                   trans('Price'), trans('Quantity'), trans("Total Price"), trans('Amount')];
            return Excel::download($export, 'sales_report.xlsx');
        }

        $orders = $orders->paginate(50);
        return view("Report::sale", compact('orders', 'statuses', 'filter', 'members', 'order_types', 'creators'));
    }


    public function serviceExpendable(Request $request){
        $filter                   = $request->all();
        $members                  = Member::getArray();
        $member_service_histories = MemberServiceHistory::query()
                                                        ->with(['memberService' => function($ms){
                                                            $ms->with('order');
                                                            $ms->with('member');
                                                        }])
                                                        ->whereHas('memberService', function($ms) use ($filter){
                                                            if (isset($filter['member_id'])) {
                                                                $ms->where('member_id', $filter['member_id']);
                                                            }
                                                        })
                                                        ->whereMonth('start', $filter['month'] ??
                                                                              formatDate(time(), 'm-Y'));
        if (isset($filter['creator'])) {
            $member_service_histories = $member_service_histories->where('updated_by', $filter['creator']);
        }

        $member_service_histories = $member_service_histories->orderBy('start', 'desc')->get();


        $location = Store::query()->where('status', Status::STATUS_ACTIVE)->first();
        $data     = [];
        foreach($member_service_histories as $key => $history) {
            $key_data                               = $history->updated_by . "-" . $history->member_service_id;
            $data[$key_data][$key]['date']          = formatDate(strtotime($history->created_at), 'd-m-Y H:i');
            $data[$key_data][$key]['order_code']    = optional($history->memberService->order)->code;
            $data[$key_data][$key]['order_id']      = optional($history->memberService->order)->id;
            $data[$key_data][$key]['location']      = $location->name;
            $data[$key_data][$key]['id_number']     = optional($history->memberService->member)->id_number;
            $data[$key_data][$key]['member_id']     = optional($history->memberService->member)->id;
            $data[$key_data][$key]['member_name']   = optional($history->memberService->member)->name;
            $data[$key_data][$key]['service_code']  = optional($history->memberService)->code;
            $data[$key_data][$key]['service_name']  = optional($history->memberService->service)->name;
            $data[$key_data][$key]['service_price'] = $history->memberService->price;
            $data[$key_data][$key]['created_by']    = optional($history->user)->name;
            $data[$key_data][$key]['order_creator'] = optional($history->memberService->creator)->name;
            $data[$key_data][$key]['check_owner']   = false;
            if ($history->updated_by == optional($history->memberService)->created_by) {
                $data[$key_data][$key]['check_owner'] = true;
            }
        }

        $total_amount = array_sum(array_column($data, 'amount'));
        $data         = $this->paginate($data, 50);
        $creators     = User::with('roles')
                            ->whereHas('roles', function($role_query){
                                $admin = Role::query()->where('name', Role::ADMINISTRATOR)->first();
                                return $role_query->whereNotIn('role_id', [$admin->id]);
                            })
                            ->where('status', Status::STATUS_ACTIVE)
                            ->pluck('name', 'id')->toArray();

        return view("Report::service_expendable", compact('filter', 'members', 'creators', 'data', 'total_amount'));
    }
}
