<?php

namespace Modules\Report\Http\Controllers;

use App\AppHelpers\Excel\Export;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Base\Model\Status;
use Modules\Member\Model\MemberService;
use Modules\Role\Model\Role;
use Modules\Service\Model\Service;
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
                          $therapist = Role::query()->where('name', Role::THERAPIST)->first();
                          return $role_query->where('role_id', $therapist->id);
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
        $member_services    = MemberService::query()->where('member_id', $id)
                                           ->where('status', MemberService::COMPLETED_STATUS)
                                           ->get();
        $data_export = [];
        foreach($member_services as $key => $value){
            $data_export[$key]['code'] = $value->code;
            $data_export[$key]['service'] = $value->service->name ?? "N/A";
            $data_export[$key]['voucher'] = $value->voucher->code  ?? "N/A";
            $data_export[$key]['remaining'] = $value->getRemaining();
            $data_export[$key]['quantity'] = $value->quantity;
            $data_export[$key]['price'] = moneyFormat($value->price, 0);
            $data_export[$key]['total_price'] = moneyFormat($value->price * $value->quantity, 0);
            $data_export[$key]['created_at'] = formatDate(strtotime($value->created_at), 'd-m-Y H:i');
        }

        $export             = new Export;
        $export->collection = collect($data_export);
        $export->headings   = [trans('Code'), trans('Service'), trans('Voucher'), trans('Remaining'),
                               trans('Quantity'), trans('Price'), trans('Total Price'), trans('Created At')];

        return Excel::download($export, 'treatment_information.xlsx');
    }


    /**
     * @param $items
     * @param int $perPage
     * @param null $page
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 20, $page = null){
        $page  = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath()
        ]);
    }
}
