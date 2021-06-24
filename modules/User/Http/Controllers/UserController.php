<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Appointment\Model\Appointment;
use Modules\Base\Model\Status;
use Modules\Order\Model\Order;
use Modules\Role\Model\Role;
use Modules\Setting\Model\CommissionRateSetting;
use Modules\User\Http\Requests\UserValidation;
use Modules\User\Model\Salary;
use Modules\User\Model\User;

class UserController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    /**
     * @return Factory|View
     */
    public function index(Request $request){
        $filter   = $request->all();
        $users    = User::filter($filter)->paginate(15);
        $statuses = Status::getStatuses();
        $roles    = Role::getArray(Status::STATUS_ACTIVE);

        return view('User::index', compact('users', 'statuses', 'filter', 'roles'));
    }

    /**
     * @return Factory|View
     */
    public function getCreate(){
        $roles    = Role::getArray();
        $statuses = Status::getStatuses();

        return view('User::create', compact('roles', 'statuses'));
    }

    /**
     * @param UserValidation $request
     *
     * @return RedirectResponse
     */
    public function postCreate(UserValidation $request){
        if(!empty($request->all()) && $request->password === $request->password_re_enter){
            $data = $request->all();
            unset($data['password_re_enter']);
            unset($data['role_id']);
            $user = new User($data);
            $user->save();
            $request->session()->flash('success', trans('User created successfully.'));
        }

        return redirect()->route('get.user.list');
    }

    /**
     * @param $id
     *
     * @return Factory|View
     */
    public function getUpdate($id){
        $roles    = Role::getArray();
        $user     = User::find($id);
        $statuses = Status::getStatuses();

        return view('User::update', compact('roles', 'user', 'statuses'));
    }

    /**
     * @param UserValidation $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function postUpdate(UserValidation $request, $id){
        $data = $request->all();
        $user = User::find($id);
        if(empty($data['password'])){
            unset($data['password']);
        }
        unset($data['password_re_enter']);
        $user->update($data);
        $request->session()->flash('success', trans('User updated successfully.'));

        return redirect()->route('get.user.list');
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function postUpdateStatus(Request $request){
        $data = $request->all();
        if($data != null){
            $user = User::find($data['id']);
            if($user){
                $user->status = $data['status'];
                $user->save();
                $request->session()->flash('success', trans('User updated successfully.'));
            }
        }
        return true;
    }

    /**
     * @return Factory|View
     */
    public function getProfile(){
        $roles          = Role::getArray();
        $user           = User::find(Auth::guard()->id());
        $statuses       = Status::getStatuses();
        $orders         = Order::query()->where('updated_by', $user->id)
                               ->whereMonth('updated_at', formatDate(time(), 'm'))
                               ->paginate(15);
        $order_statuses = Order::getStatus();

        return view('User::update', compact('roles', 'user', 'statuses', 'orders', 'order_statuses'));
    }

    /**
     * @param UserValidation $request
     *
     * @return RedirectResponse
     */
    public function postProfile(UserValidation $request){
        $data = $request->all();
        $user = User::find(Auth::guard()->id());
        if(empty($data['password'])){
            unset($data['password']);
        }
        unset($data['password_re_enter']);
        $user->update($data);
        $request->session()->flash('success', trans('User updated successfully.'));

        return redirect()->route('dashboard');
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
        $orders         = $orders->paginate(15);
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
        $salary->save();

        $request->session()->flash('success', trans('Update Basic Salary successfully.'));

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
        $salary->save();

        $request->session()->flash('success', trans('Reload successfully.'));

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

        foreach($users as $user){
            $salary = Salary::query()->where('user_id', $user->id)->where('month', formatDate(time(), 'm/Y'))->first();
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
            $salary->save();
        }

        $request->session()->flash('success', trans('Reload successfully.'));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, $id){
        $user = User::find($id);
        $user->delete();
        $request->session()->flash('success', trans('User deleted successfully.'));

        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function getAppointment(Request $request, $id){
        $filter            = $request->all();
        $user              = User::find($id);
        $appointment_types = Appointment::getTypeList();
        $appointments      = Appointment::with('member')
                                        ->with('store')
                                        ->with('user');
        $appointments      = $appointments->where('user_id', $id);
        /** Created_by */
        if(!Auth::user()->isAdmin()){
            $appointments = $appointments->where('user_id', Auth::id());
        }

        /** Type of appointment */
        if(isset($filter['type'])){
            $appointments = $appointments->where('type', $filter['type']);
        }else{
            $appointments = $appointments->where('type', Appointment::SERVICE_TYPE);
        }

        $appointments = $appointments->get();

        /** Get event */
        $events = [];
        foreach($appointments as $appointment){
            $title    = (Auth::user()->isAdmin())
                ? $appointment->member->name . ' | ' . $appointment->user->name
                : $appointment->member->name . ' | ' . $appointment->name;
            $events[] = [
                'id'    => $appointment->id,
                'title' => $title,
                'start' => Carbon::parse($appointment->time)
                                 ->format('Y-m-d H:i'),
                'end'   => (!empty($appointment->end_time)) ? Carbon::parse($appointment->end_time)
                                                                    ->format('Y-m-d H:i') : null,
                'color' => $appointment->getColorStatus()
            ];
        }
        $events = json_encode($events);
        return view("Appointment::index", compact('events', 'appointment_types', 'filter', 'user'));
    }

}
