<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Member\Model\MemberServiceHistory;
use Modules\Order\Model\Order;
use Modules\Role\Model\Role;
use Modules\Setting\Model\CommissionRateSetting;
use Modules\User\Model\Salary;
use Modules\User\Model\User;
use Throwable;

class SalaryController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    /**
     * @param $id
     * @return Application|Factory|RedirectResponse|View
     */
    public function getSalary($id){
        $user = User::find($id);
        if($user->isAdmin()){
            return redirect()->back();
        }
        $salary    = Salary::query()->where('user_id', $id)->where('month', formatDate(time(), 'm/Y'))->first();
        $target_by = $user->getTargetBy();
        $orders    = Order::query();
        if($target_by === CommissionRateSetting::PERSON_INCOME){
            $orders->where('updated_by', $user->id);
        }
        $orders->whereMonth('updated_at', formatDate(time(), 'm'));
        $orders         = $orders->paginate(50);
        $order_statuses = Order::getStatus();

        return view('User::salary.salary', compact('user', 'orders', 'order_statuses', 'salary', 'target_by'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|RedirectResponse|string
     */
    public function getUpdateSalary(Request $request, $id){
        $user   = User::find($id);
        $salary = Salary::query()
                        ->where('user_id', $id)
                        ->where('month', formatDate(time(), 'm/Y'))
                        ->first();

        if(!$request->ajax()){
            return redirect()->back();
        }

        return $this->renderAjax('User::salary.form', compact('salary', 'user'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function postUpdateSalary(Request $request, $id){
        $user               = User::find($id);
        $user->basic_salary = $request->basic_salary;
        $user->save();

        $salary = Salary::query()->where('user_id', $id)->where('month', formatDate(time(), 'm/Y'))->first();

        DB::beginTransaction();
        try{
            if(empty($salary)){
                $salary          = new Salary();
                $salary->user_id = $id;
                $salary->month   = formatDate(time(), 'm/Y');
            }
            $salary->basic_salary       = $user->basic_salary;
            $salary->sale_commission    = $salary->getSaleCommission();
            $salary->service_commission = $salary->getServiceCommission();
            $salary->company_commission = $salary->getCompanyIncomeCommission();
            $salary->total_commission   = $salary->getTotalCommission();
            $salary->total_salary       = $salary->getTotalSalary();
            $salary->payment_rate       = $user->getCommissionRate();
            $salary->service_rate       =
                CommissionRateSetting::getValueByKey(CommissionRateSetting::SERVICE_RATE) ?? 0;
            $salary->save();

            DB::commit();
            $request->session()->flash('success', trans('Update Basic Salary successfully.'));
        }catch(Throwable $th){
            DB::rollBack();
            $request->session()->flash('danger', trans('Update Basic Salary failed.'));
        }

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function singleReloadSalary(Request $request, $id){
        $user   = User::find($id);
        $salary = Salary::query()->where('user_id', $id)->where('month', formatDate(time(), 'm/Y'))->first();
        DB::beginTransaction();
        try{
            if(empty($salary)){
                $salary          = new Salary();
                $salary->user_id = $id;
                $salary->month   = formatDate(time(), 'm/Y');
            }
            $salary->basic_salary       = $user->basic_salary;
            $salary->sale_commission    = $salary->getSaleCommission();
            $salary->service_commission = $salary->getServiceCommission();
            $salary->company_commission = $salary->getCompanyIncomeCommission();
            $salary->total_commission   = $salary->getTotalCommission();
            $salary->total_salary       = $salary->getTotalSalary();
            $salary->payment_rate       = $user->getCommissionRate();
            $salary->service_rate       =
                CommissionRateSetting::getValueByKey(CommissionRateSetting::SERVICE_RATE) ?? 0;
            $salary->save();

            DB::commit();
            $request->session()->flash('success', trans('Calculate Salary successfully.'));
        }catch(Throwable $th){
            DB::rollBack();
            $request->session()->flash('danger', trans('Calculate Salary failed.'));
        }

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function bulkReloadSalary(Request $request){
        $users = User::query()->whereHas('roles', function($qr){
            $qr->where('role_id', '<>', Role::getAdminRole()->id);
        })->get();
        DB::beginTransaction();
        try{
            foreach($users as $user){
                $salary = Salary::query()
                                ->where('user_id', $user->id)
                                ->where('month', formatDate(time(), 'm/Y'))
                                ->first();
                if(empty($salary)){
                    $salary          = new Salary();
                    $salary->user_id = $user->id;
                    $salary->month   = formatDate(time(), 'm/Y');
                }
                $salary->basic_salary       = $user->basic_salary;
                $salary->sale_commission    = $salary->getSaleCommission();
                $salary->service_commission = $salary->getServiceCommission();
                $salary->company_commission = $salary->getCompanyIncomeCommission();
                $salary->total_commission   = $salary->getTotalCommission();
                $salary->total_salary       = $salary->getTotalSalary();
                $salary->payment_rate       = $user->getCommissionRate();
                $salary->service_rate       =
                    CommissionRateSetting::getValueByKey(CommissionRateSetting::SERVICE_RATE) ?? 0;
                $salary->save();
            }

            DB::commit();
            $request->session()->flash('success', trans('Bulk Calculate Salary successfully.'));
        }catch(Throwable $th){
            DB::rollBack();
            $request->session()->flash('danger', trans('Bulk Calculate Salary failed.'));
        }

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|RedirectResponse|string
     */
    public function getSupplyHistoryService(Request $request, $id){
        $user      = User::query()->find($id);
        $histories = MemberServiceHistory::query()
                                         ->whereMonth('updated_at', formatDate(time(), 'm'))
                                         ->where('updated_by', $user->id)
                                         ->paginate(50);

        if(!$request->ajax()){
            return redirect()->back();
        }

        return $this->renderAjax('User::salary.supply_history_list', compact('histories'));
    }

}
