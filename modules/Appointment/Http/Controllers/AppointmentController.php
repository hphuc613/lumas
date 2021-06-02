<?php

namespace Modules\Appointment\Http\Controllers;

use App\AppHelpers\Helper;
use App\Http\Controllers\Controller;
use App\Notifications\Notification;
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
use Modules\Course\Model\Course;
use Modules\Member\Model\Member;
use Modules\Role\Model\Role;
use Modules\Service\Model\Service;
use Modules\Store\Model\Store;
use Modules\User\Model\User;
use Pusher\Pusher;
use Pusher\PusherException;

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
    public function index(Request $request){
        if($request->session()->has('member_display_id')){
            $request->session()->forget('member_display_id');
            $request->session()->save();
        }
        $filter            = $request->all();
        $appointment_types = Appointment::getTypeList();
        $appointments      = Appointment::with('member')
                                        ->with('store')
                                        ->with('user');

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
                'color' => $appointment->getColorStatus()
            ];
        }
        $events = json_encode($events);
        return view("Appointment::index", compact('events', 'appointment_types', 'filter'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function getCreate(Request $request){
        $statuses          = Appointment::getStatuses();
        $services          = Service::getArray(Status::STATUS_ACTIVE);
        $courses           = Course::getArray(Status::STATUS_ACTIVE);
        $appointment_types = Appointment::getTypeList();
        $members           = Member::getArray(Status::STATUS_ACTIVE);
        $stores            = Store::getArray(Status::STATUS_ACTIVE);
        $users             = [];
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
        return view("Appointment::form", compact('statuses', 'appointment_types', 'services', 'courses', 'members', 'stores', 'users'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function postCreate(AppointmentRequest $request){
        $data = $request->all();

        /** Get list id service/course*/
        if($data['type'] === Appointment::SERVICE_TYPE){
            $data['service_ids'] = json_encode($data['product_ids'] ?? []);
        }else{
            $data['course_ids'] = json_encode($data['product_ids'] ?? []);
        }
        unset($data['product_ids']);

        if(!isset($data['user_id']) || empty($data['user_id'])){
            $data['user_id'] = Auth::id();
        }
        $data['time'] = Carbon::parse($data['time'])
                              ->format('Y-m-d H:i');
        $book         = new Appointment($data);
        $book->save();
        $request->session()->flash('success', 'Appointment booked successfully.');

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function getUpdate(Request $request, $id){
        $statuses = Appointment::getStatuses();
        $members  = Member::getArray(Status::STATUS_ACTIVE);
        $stores   = Store::getArray(Status::STATUS_ACTIVE);

        $appointment_types = Appointment::getTypeList();

        $appointment       = Appointment::with('member')
                                        ->with('store')
                                        ->with('user')
                                        ->find($id);
        $appointment->time = Carbon::parse($appointment->time)
                                   ->format('d-m-Y H:i');

        $users = [];
        if(Auth::user()->isAdmin()){
            $users = User::with('roles')
                         ->whereHas('roles', function($role_query){
                             return $role_query->where('role_id', '<>', Role::getAdminRole()->id);
                         })
                         ->where('status', Status::STATUS_ACTIVE)->pluck('name', 'id');
        }
        $services                 = Service::getArray(Status::STATUS_ACTIVE, false, Helper::isJson($appointment->service_ids, 1));
        $courses                  = Course::getArray(Status::STATUS_ACTIVE, false, Helper::isJson($appointment->course_ids, 1));
        $appointment->service_ids = $appointment->getServiceList();
        $appointment->course_ids  = $appointment->getCourseList();

        if(!$request->ajax()){
            return redirect()->back();
        }

        return view("Appointment::form", compact('statuses', 'appointment_types', 'services', 'courses', 'members', 'stores', 'appointment', 'services', 'users'));
    }

    /**
     * @param AppointmentRequest $request
     * @return RedirectResponse
     */
    public function postUpdate(AppointmentRequest $request, $id){
        $book = Appointment::find($id);
        $data = $request->all();
        /** Get list id service/course*/
        if($data['type'] === Appointment::SERVICE_TYPE){
            $data['service_ids'] = json_encode($data['product_ids'] ?? []);
        }else{
            $data['course_ids'] = json_encode($data['product_ids'] ?? []);
        }
        unset($data['product_ids']);
        $data['time'] = Carbon::parse($data['time'])
                              ->format('Y-m-d H:i');
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

        return redirect()->back();
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
     * @param $id
     * @return RedirectResponse
     */
    public function checkIn(Request $request, $id, $member_id){
        $appointment                   = Appointment::query();
        $check_appointment_progressing = clone $appointment;
        $check_appointment_progressing->where('status', Appointment::PROGRESSING_STATUS)
                                      ->where('member_id', $member_id);
        $check_appointment_progressing = $check_appointment_progressing->first();
        if(!empty($check_appointment_progressing)){
            $request->session()->flash('success', trans("There is an appointment in progressing."));

            return redirect()->route('get.member_service.add', $check_appointment_progressing->member_id);
        }

        $appointment         = $appointment->find($id);
        $appointment->status = Appointment::PROGRESSING_STATUS;
        $appointment->save();
        $request->session()->flash('success', trans("This appointment in progressing."));

        return redirect()->route('get.member_service.add', $appointment->member_id);
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function checkOut(Request $request, $id){
        $appointment = Appointment::where('member_id', $id)->where('status', Appointment::PROGRESSING_STATUS)
                                  ->first();

        if($appointment->member->checkServiceInProgressing()){
            $request->session()->flash('error', trans("There are services in progressing."));

            return redirect()->back();
        }
        $appointment->status = Appointment::COMPLETED_STATUS;
        $appointment->save();
        $request->session()->flash('success', trans("This appointment is completed."));

        return redirect()->route('get.member.appointment', $appointment->member_id);
    }

    /**
     * @return Application|Factory|View
     */
    public function getNotification(){
        return view("Appointment::notification");
    }

    public function postNotification(Request $request){
        $user = Auth::user();
        $data = $request->only([
            'title',
            'content',
        ]);
        $user->notify(new Notification($data));

        $options = [
            'cluster'   => 'ap1',
            'encrypted' => true
        ];
        try{
            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );
        }catch(PusherException $e){
            $request->session()->flash('error', $e->getMessage());
        }

        $pusher->trigger('NotificationEvent', 'send-message', $data);


        return true;
    }
}
