<?php

namespace Modules\Appointment\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Appointment\Http\Requests\AppointmentRequest;
use Modules\Appointment\Model\Appointment;
use Modules\Base\Model\Status;
use Modules\Member\Model\Member;
use Modules\Role\Model\Role;
use Modules\Service\Model\Service;
use Modules\Service\Model\ServiceType;
use Modules\Store\Model\Store;
use Modules\User\Model\User;

class AppointmentController extends Controller{

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
    public function index(){
        $appointments = Appointment::with('member')
                                   ->with('service')
                                   ->with('store')
                                   ->with('user');
        if(!Auth::user()->isAdmin()){
            $appointments = $appointments->where('user_id', Auth::id());
        }
        $appointments = $appointments->get();
        $events       = [];
        foreach($appointments as $appointment){
            $title    = (Auth::user()
                             ->isAdmin()) ? $appointment->name . ' | ' . $appointment->user->name : $appointment->name;
            $events[] = [
                'id'    => $appointment->id,
                'title' => $title,
                'start' => Carbon::parse($appointment->time)
                                 ->format('Y-m-d H:i'),
                'color' => (strtotime($appointment->time) < time()) ? '#aaa' : '#16c8ee'
            ];
        }
        $events = json_encode($events);
        return view("Appointment::index", compact('events'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function getCreate(Request $request){
        $statuses      = Status::STATUSES;
        $service_types = ServiceType::getArray(Status::STATUS_ACTIVE);
        $clients       = Member::getArray(Status::STATUS_ACTIVE);
        $stores        = Store::getArray(Status::STATUS_ACTIVE);
        $users         = [];
        if(Auth::user()->isAdmin()){
            $users = User::with('roles')
                         ->whereHas('roles', function($role_query){
                             return $role_query->where('role_id', '<>', Role::getAdminRole()->id);
                         })
                         ->where('status', Status::STATUS_ACTIVE)->pluck('name', 'id');
        }
        if(!$request->ajax()){
            return redirect()->back();
        }
        return view("Appointment::form", compact('statuses', 'service_types', 'clients', 'stores', 'users'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function postCreate(AppointmentRequest $request){
        $data            = $request->all();
        $data['user_id'] = Auth::id();
        $data['time']    = Carbon::parse($data['time'])
                                 ->format('Y-m-d H:i');
        $book            = new Appointment($data);
        $book->save();
        $request->session()
                ->flash('success',
                    'Appointment booked successfully.');

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function getUpdate(Request $request, $id){
        $statuses          = Status::STATUSES;
        $service_types     = ServiceType::getArray(Status::STATUS_ACTIVE);
        $clients           = Member::getArray(Status::STATUS_ACTIVE);
        $stores            = Store::getArray(Status::STATUS_ACTIVE);
        $appointment       = Appointment::with('member')
                                        ->with('service')
                                        ->with('store')
                                        ->with('user')
                                        ->find($id);
        $appointment->time = Carbon::parse($appointment->time)
                                   ->format('d-m-Y H:i');
        $users             = [];
        if(Auth::user()->isAdmin()){
            $users = User::with('roles')
                         ->whereHas('roles', function($role_query){
                             return $role_query->where('role_id', '<>', Role::getAdminRole()->id);
                         })
                         ->where('status', Status::STATUS_ACTIVE)->pluck('name', 'id');
        }

        $services = Service::with("type")
                           ->where("status", Status::STATUS_ACTIVE)
                           ->where('type_id', $appointment->service->type_id)
                           ->pluck('name', 'id')
                           ->toArray();

        if(!$request->ajax()){
            return redirect()->back();
        }

        return view("Appointment::form", compact('statuses', 'service_types', 'clients', 'stores', 'appointment', 'services', 'users'));
    }

    /**
     * @param AppointmentRequest $request
     * @return RedirectResponse
     */
    public function postUpdate(AppointmentRequest $request, $id){
        $data         = $request->all();
        $data['time'] = Carbon::parse($data['time'])
                              ->format('Y-m-d H:i');
        $book         = Appointment::find($id);
        $book->update($data);

        $request->session()
                ->flash('success',
                    'Appointment updated successfully.');

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function delete(Request $request, $id){
        $appointment = Appointment::find($id);
        $appointment->delete();
        $request->session()
                ->flash('success',
                    'Appointment deleted successfully.');

        return redirect()->route('get.appointment.list');
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|int[]
     */
    public function postChangeTime(Request $request, $id){
        $data         = $request->all();
        $data['time'] = Carbon::parse($data['time'])
                              ->format('Y-m-d H:i');
        $appointment  = Appointment::find($id);
        try{
            $appointment->update($data);
            return [
                'status'    => 200,
                'past_time' => (strtotime($appointment->time) < time())
            ];
        }catch(Exception $e){
            return [
                'status'  => 400,
                'message' => (string)$e->getMessage()
            ];
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return string
     */
    public function getListServiceByType(Request $request, $id){

        $services = Service::where('status', Status::STATUS_ACTIVE)
                           ->where('type_id', $id)
                           ->orderBy('name', 'asc')
                           ->pluck('name', 'id')
                           ->toArray();
        $html     = "<option value=''>Select</option>";
        foreach($services as $key => $service){
            $html .= "<option value='$key'>$service</option>";
        }

        return $html;
    }

}
