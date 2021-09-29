<?php

namespace Modules\Appointment\Http\Controllers;

use App\AppHelpers\Helper;
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
use Modules\Course\Model\Course;
use Modules\Member\Model\Member;
use Modules\Service\Model\Service;
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
    public function index(Request $request){
        $filter            = $request->all();
        $appointment_types = Appointment::getTypeList();
        $appointments      = Appointment::with('member')
                                        ->with('store')
                                        ->with('user');

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
                ? ($appointment->member->name ?? "N/A") . ' | ' . ($appointment->user->name ?? "N/A")
                : ($appointment->member->name ?? "N/A") . ' | ' . $appointment->name;
            $events[] = [
                'id'    => $appointment->id,
                'title' => $title,
                'start' => formatDate($appointment->time, 'Y-m-d H:i'),
                'color' => $appointment->getColorStatus()
            ];
        }
        $events = json_encode($events);
        return view("Appointment::index", compact('events', 'appointment_types', 'filter'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function overview(Request $request){
        $filter            = $request->all();
        $members           = Member::getArray();
        $statuses          = Appointment::getStatuses();
        $stores            = Store::getArray(Status::STATUS_ACTIVE);
        $appointment_types = Appointment::getTypeList();
        $appointments      = Appointment::filter($filter);

        /** Created_by */
        if(Auth::user()->getRoleAttribute()->id == 3){
            $appointments = $appointments->where('user_id', Auth::id());
        }

        $appointments = $appointments->orderBy('time', 'DESC')->paginate(6);

        return view("Appointment::overview", compact('appointments', 'appointment_types', 'filter', 'members', 'statuses', 'stores'));
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
        $users             = User::with('roles')
                                 ->whereHas('roles', function($role_query){
                                     return $role_query->where('role_id', 3);
                                 })
                                 ->where('status', Status::STATUS_ACTIVE)
                                 ->pluck('name', 'id');
        if(!$request->ajax()){
            return redirect()->back();
        }
        return view("Appointment::detail", compact('statuses', 'appointment_types', 'services', 'courses', 'members', 'stores', 'users'));
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
        $request->session()->flash('success', trans('Appointment booked successfully.'));

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

        $appointment           = Appointment::with('member')
                                            ->with('store')
                                            ->with('user')
                                            ->find($id);
        $appointment->time     = formatDate(strtotime($appointment->time), 'd-m-Y H:i');
        $appointment->end_time =
            (!empty($appointment->end_time)) ? formatDate($appointment->end_time, 'd-m-Y H:i') : null;

        $users                    = User::with('roles')
                                        ->whereHas('roles', function($role_query){
                                            return $role_query->where('role_id', 3);
                                        })
                                        ->where('status', Status::STATUS_ACTIVE)->pluck('name', 'id');
        $services                 =
            Service::getArray(Status::STATUS_ACTIVE, false, Helper::isJson($appointment->service_ids, 1));
        $courses                  =
            Course::getArray(Status::STATUS_ACTIVE, false, Helper::isJson($appointment->course_ids, 1));
        $appointment->service_ids = $appointment->getServiceList();
        $appointment->course_ids  = $appointment->getCourseList();

        if(!$request->ajax()){
            return redirect()->back();
        }

        return view("Appointment::detail", compact('statuses', 'appointment_types', 'services', 'courses', 'members', 'stores', 'appointment', 'services', 'users'));
    }

    /**
     * @param AppointmentRequest $request
     * @return RedirectResponse
     */
    public function postUpdate(AppointmentRequest $request, $id){
        $book        = Appointment::find($id);
        $appointment = $book->member->getAppointmentInProgressing();
        if ($appointment && $appointment->id == $id) {
            if ($book->member->checkServiceInProgressing()) {
                $request->session()->flash('error', trans("There are services in progress."));

                return redirect()->back();
            }
            if ($book->member->checkCourseInProgressing()) {
                $request->session()->flash('error', trans("There are courses in progress."));

                return redirect()->back();
            }
        }

        $data = $request->all();

        /** Get list id service/course*/
        if($data['type'] === Appointment::SERVICE_TYPE){
            $data['service_ids'] = json_encode($data['product_ids'] ?? []);
        }else{
            $data['course_ids'] = json_encode($data['product_ids'] ?? []);
        }
        unset($data['product_ids']);
        $data['time'] = formatDate($data['time'], 'Y-m-d H:i');
        if(isset($data['end_time'])){
            $data['end_time'] = formatDate($data['end_time'], 'Y-m-d H:i');
        }
        $book->update($data);

        $request->session()->flash('success', trans('Appointment updated successfully.'));

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
                    trans('Appointment deleted successfully.'));

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

        /** Check appointment of member progressing */
        $check_appointment_progressing->where('status', Appointment::PROGRESSING_STATUS)
                                      ->where('member_id', $member_id)
                                      ->where('id', '<>', $id);
        $check_appointment_progressing = $check_appointment_progressing->first();
        if(!empty($check_appointment_progressing)){
            $request->session()->flash('error', trans("There is an appointment in progress."));

            return redirect()->route('get.member_service.add', $check_appointment_progressing->member_id);
        }

        /** Check appointment of staff progressing */
        $check_user_progressing = clone $appointment;
        $appointment            = $appointment->find($id); //Get current appointment
        $check_user_progressing->where('status', Appointment::PROGRESSING_STATUS)
                               ->where('user_id', $appointment->user_id)
                               ->where('id', '<>', $id);
        $check_user_progressing = $check_user_progressing->first();
        if(!empty($check_user_progressing)){
            $request->session()->flash('error', trans("Staff of this appointment is in progress."));

            return redirect()->route('get.member_service.add', $check_user_progressing->member_id);
        }

        /** Check In */
        if($appointment->status !== Appointment::PROGRESSING_STATUS){
            $appointment->status     = Appointment::PROGRESSING_STATUS;
            $appointment->start_time = formatDate(time(), 'Y-m-d H:i:s');
            $appointment->save();
        }

        $request->session()->flash('success', trans("This appointment in progress."));

        if($appointment->type === Appointment::COURSE_TYPE){
            return redirect()->route('get.member_course.add', $appointment->member_id);
        }
        return redirect()->route('get.member_service.add', $appointment->member_id);
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function checkOut(Request $request, $id){
        $appointment = Appointment::where('member_id', $id)
                                  ->where('status', Appointment::PROGRESSING_STATUS)
                                  ->first();

        if($appointment->member->checkServiceInProgressing()){
            $request->session()->flash('error', trans("There are services in progress."));

            return redirect()->back();
        }
        if($appointment->member->checkCourseInProgressing()){
            $request->session()->flash('error', trans("There are courses in progress."));

            return redirect()->back();
        }
        $appointment->status   = Appointment::COMPLETED_STATUS;
        $appointment->end_time = formatDate(time(), 'Y-m-d H:i:s');
        $appointment->save();
        $request->session()->flash('success', trans("This appointment is completed."));

        return redirect()->route('get.member.appointment', [$appointment->member_id, 'type' => $appointment->type]);
    }

    public function getProductList($id){
        $appointment = Appointment::query()->find($id);

        return $this->renderAjax('Appointment::product_list', compact('appointment'));
    }
}
