<?php

namespace Modules\Appointment\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Appointment\Http\Requests\AppointmentRequest;
use Modules\Appointment\Http\Requests\BulkAppointmentRequest;
use Modules\Appointment\Model\Appointment;
use Modules\Base\Model\Status;
use Modules\Course\Model\Course;
use Modules\Instrument\Model\Instrument;
use Modules\Member\Model\Member;
use Modules\Notification\Model\NotificationModel;
use Modules\Role\Model\Role;
use Modules\Room\Model\Room;
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
        if (isset($filter['type'])) {
            $appointments = $appointments->where('type', $filter['type']);
        } else {
            $appointments = $appointments->where('type', Appointment::SERVICE_TYPE);
        }

        $now          = Carbon::now();
        $appointments = $appointments->whereMonth('time', $now->month)
                                     ->whereYear('time', $now->year)
                                     ->get();

        $events = json_encode($this->getEvents($appointments));
        return view("Appointment::index", compact('events', 'appointment_types', 'filter'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getEventList(Request $request){
        $filter       = $request->all();
        $appointments = Appointment::with('member')
                                   ->with('store')
                                   ->with('user');

        if (isset($request->member_id)) {
            $appointments = $appointments->where('member_id', $request->member_id);
        }

        if (isset($request->user_id)) {
            $appointments = $appointments->where('user_id', $request->user_id);
        }

        /** Type of appointment */
        if (isset($filter['type'])) {
            $appointments = $appointments->where('type', $filter['type']);
        } else {
            $appointments = $appointments->where('type', Appointment::SERVICE_TYPE);
        }
        if (isset($request->month)) {
            $start = Carbon::createFromFormat('m-Y', ($request->month - 1) . '-' . $request->year)->startOfMonth();
            $end   = Carbon::createFromFormat('m-Y', ($request->month + 1) . '-' . $request->year)->endOfMonth();
            if ($request->month == 12) {
                $end = Carbon::createFromFormat('m-Y', 1 . '-' . ($request->year + 1))->endOfMonth();
            }

            $appointments = $appointments->whereBetween('time', [$start, $end]);
        }
        $appointments = $appointments->get();

        /** Get event */
        $events = $this->getEvents($appointments);

        return response()->json(json_encode($events));
    }

    /**
     * @param $appointments
     * @return array
     */
    public function getEvents($appointments){
        /** Get event */
        $events = [];
        foreach($appointments as $appointment) {
            $title    = (Auth::user()->isAdmin())
                ? ($appointment->member->name ?? "N/A") . ' | ' . ($appointment->user->name ?? "N/A")
                : ($appointment->member->name ?? "N/A") . ' | ' . $appointment->name;
            $events[] = [
                'id'           => $appointment->id,
                'title'        => $title,
                'start'        => formatDate($appointment->time, 'Y-m-d H:i'),
                'end'          => formatDate(strtotime($appointment->time) +
                                             (($appointment->getTotalIntendTimeService()) * 60), 'Y-m-d H:i'),
                'color'        => $appointment->getColorStatus(),
                'product_list' => $this->getProductList($appointment)
            ];
        }
        return $events;
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

        $appointments = $appointments->orderBy('time', 'DESC')->paginate(50);

        return view("Appointment::overview", compact('appointments', 'appointment_types', 'filter', 'members', 'statuses', 'stores'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function getCreate(Request $request){
        $statuses          = Appointment::getStatuses();
        $services          = Service::getArray(Status::STATUS_ACTIVE);
        $rooms             = Room::getArray(Status::STATUS_ACTIVE);
        $instruments       = Instrument::getArray(Status::STATUS_ACTIVE);
        $appointment_types = Appointment::getTypeList();
        $members           = Member::getArray(Status::STATUS_ACTIVE);
        $stores            = Store::getArray(Status::STATUS_ACTIVE);
        $users             = User::with('roles')
                                 ->whereHas('roles', function($role_query){
                                     $admin = Role::query()->where('name', Role::ADMINISTRATOR)->first();
                                     return $role_query->whereNotIn('role_id', [$admin->id]);
                                 })
                                 ->where('status', Status::STATUS_ACTIVE)
                                 ->pluck('name', 'id');

        if (!$request->ajax()) {
            return redirect()->back();
        }
        return view("Appointment::detail", compact('statuses', 'appointment_types', 'services', 'members', 'stores', 'users', 'rooms', 'instruments'));
    }

    /**
     * @param AppointmentRequest $request
     * @return RedirectResponse
     */
    public function postCreate(AppointmentRequest $request){
        $data        = $request->all();
        $data        = $this->createAppointment($data);
        $assign_more = $data['assign_more'];
        unset($data['assign_more']);
        $data['time'] = Carbon::parse($data['time'])
                              ->format('Y-m-d H:i');
        $book         = new Appointment($data);
        $book->save();
        $book->staffs()->sync($assign_more);
        foreach($assign_more as $staff_id) {
            $book->addNotification($staff_id);
        }
        $request->session()->flash('success', trans('Appointment booked successfully.'));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function getBulkCreate(Request $request){
        $statuses          = Appointment::getStatuses();
        $services          = Service::getArray(Status::STATUS_ACTIVE);
        $courses           = Course::getArray(Status::STATUS_ACTIVE);
        $rooms             = Room::getArray(Status::STATUS_ACTIVE);
        $instruments       = Instrument::getArray(Status::STATUS_ACTIVE);
        $appointment_types = Appointment::getTypeList();
        $members           = Member::getArray(Status::STATUS_ACTIVE);
        $stores            = Store::getArray(Status::STATUS_ACTIVE);
        $users             = User::with('roles')
                                 ->whereHas('roles', function($role_query){
                                     $admin = Role::query()->where('name', Role::ADMINISTRATOR)->first();
                                     return $role_query->whereNotIn('role_id', [$admin->id]);
                                 })
                                 ->where('status', Status::STATUS_ACTIVE)
                                 ->pluck('name', 'id')->toArray();
        if (!$request->ajax()) {
            return redirect()->back();
        }
        return view("Appointment::bulk_form", compact('statuses', 'appointment_types', 'services', 'courses', 'members', 'stores', 'users', 'rooms', 'instruments'));
    }

    /**
     * @param BulkAppointmentRequest $request
     * @return RedirectResponse
     */
    public function postBulkCreate(BulkAppointmentRequest $request){
        $data = request()->except(['_token']);
        unset($data['day_of_week'], $data['time'], $data['from'], $data['to'], $data['assign_more']);
        $date_start  = Carbon::parse($request->from)->timestamp;
        $date_end    = Carbon::parse($request->to)->timestamp + 86400;
        $time        = $request->time;
        $days        = $request->day_of_week;
        $a_week      = 86400 * 7;
        $data_insert = collect();
        $data        = $this->createAppointment($data);
        $assign_more = $data['assign_more'];
        unset($data['assign_more']);
        foreach($days as $day) {
            $day_next_week = Carbon::parse($date_start)->next($day)->timestamp;
            while($date_start <= $day_next_week && $day_next_week < $date_end) {
                $data['notify_created'] = 0;
                $data['time']           = formatDate($day_next_week, 'Y-m-d') . ' ' . $time;
                $data_insert->push($data);

                $day_next_week += $a_week;
            }
        }
        foreach($data_insert->chunk(200) as $chunk) {
            Appointment::query()->insert($chunk->toArray());
        }

        /** Add Notification */
        $appointments       = Appointment::query();
        $clone_appointments = clone $appointments;
        $clone_appointments = $clone_appointments->with('member')
                                                 ->where('member_id', $data['member_id'])
                                                 ->where('user_id', $data['user_id'])
                                                 ->where('notify_created', 0)
                                                 ->get();

        $notifications = collect();
        foreach($clone_appointments as $appointment) {
            $appointment->staffs()->sync($assign_more);

            $data_notify['id']              = Str::orderedUuid();
            $data_notify['type']            = 'App\Notifications\Notification';
            $data_notify['notifiable_type'] = 'Modules\User\Model\User';
            $data_notify['notifiable_id']   = $appointment->user_id;
            $data_notify['data']            = json_encode([
                'appointment_id'   => (int)$appointment->id,
                'title'            => $appointment->name,
                'member'           => $appointment->member->name,
                'member_id'        => (int)$appointment->member_id,
                'user_id'          => (int)$appointment->user_id,
                'time'             => $appointment->time,
                'type'             => $appointment->type,
                'status'           => Status::STATUS_PENDING,
                'time_show'        => NULL,
                'user_time_show'   => NULL,
                'user_read_at'     => NULL,
                'client_read_at'   => NULL,
                'client_time_show' => NULL,
            ]);

            $notifications->push($data_notify);

            if (!empty($assign_more)) {
                foreach($assign_more as $val) {
                    $data_notify['id']            = Str::orderedUuid();
                    $data_notify['notifiable_id'] = (int)$val;

                    $notifications->push($data_notify);
                }
            }
        }

        foreach($notifications->chunk(200) as $chunk) {
            NotificationModel::query()->insert($chunk->toArray());
        }

        $appointments->update(['notify_created' => 1]);
        $request->session()->flash('success', trans('Appointment booked successfully.'));

        return redirect()->back();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createAppointment($data){
        /** Get list id service/course*/
        $assign_more = [];
        $assign      = [];
        $service_ids = [];
        foreach($data['assign'] as $key => $item) {
            if (isset($item['staff'])) {
                $assign_more[] = (int)$item['staff'];
            }
            if (isset($item['service'])) {
                $service_ids[] = $item['service'];
            }

            if ($key == 1) {
                $assign[$key]['staff_id']   = $data['user_id'];
                $assign[$key]['service_id'] = $item['service'];
            } else {
                $assign[$key]['staff_id']   = $item['staff'];
                $assign[$key]['service_id'] = $item['service'];
            }
        }

        $data['assign']        = json_encode($assign);
        $data['service_ids']   = json_encode($service_ids);
        $data['instrument_id'] = json_encode($data['instrument_id'] ?? []);
        $data['room_id']       = json_encode($data['room_id'] ?? []);

        if (!isset($data['user_id']) || empty($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }

        $data['assign_more'] = $assign_more;
        return $data;
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function getUpdate(Request $request, $id){
        $statuses    = Appointment::getStatuses();
        $members     = Member::getArray(Status::STATUS_ACTIVE);
        $stores      = Store::getArray(Status::STATUS_ACTIVE);
        $rooms       = Room::getArray(Status::STATUS_ACTIVE);
        $instruments = Instrument::getArray(Status::STATUS_ACTIVE);

        $appointment_types = Appointment::getTypeList();

        $appointment           = Appointment::with('member')
                                            ->with('store')
                                            ->with('user')
                                            ->find($id);
        $appointment->time     = formatDate(strtotime($appointment->time), 'd-m-Y H:i');
        $appointment->end_time = (!empty($appointment->end_time)) ? formatDate($appointment->end_time, 'd-m-Y H:i') :
            null;

        $users    = User::with('roles')
                        ->whereHas('roles', function($role_query){
                            $admin = Role::query()->where('name', Role::ADMINISTRATOR)->first();
                            return $role_query->whereNotIn('role_id', [$admin->id]);
                        })
                        ->where('status', Status::STATUS_ACTIVE)->pluck('name', 'id');
        $services = Service::getArray(Status::STATUS_ACTIVE);

        $appointment->service_ids = $appointment->getServiceList();
        $appointment->course_ids  = $appointment->getCourseList();


        $assign = !empty($appointment->getAssign()) ? $appointment->getAssign() : [];

        if (!$request->ajax()) {
            return redirect()->back();
        }

        return view("Appointment::detail",
            compact('statuses', 'appointment_types', 'members', 'stores',
                'appointment', 'services', 'users', 'rooms', 'instruments', 'assign'));
    }

    /**
     * @param AppointmentRequest $request
     * @return RedirectResponse
     */
    public function postUpdate(AppointmentRequest $request, $id){
        $book   = Appointment::find($id);
        $member = Member::find($book->member_id);
        if (!empty($member)) {
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
        }

        $data = $request->all();

        $assign_more = [];
        $assign      = [];
        $service_ids = [];
        foreach($request->assign as $key => $item) {
            if (isset($item['staff'])) {
                $assign_more[] = (int)$item['staff'];
            }
            if (isset($item['service'])) {
                $service_ids[] = $item['service'];
            }

            if ($key == 1) {
                $assign[$key]['staff_id']   = $request->user_id;
                $assign[$key]['service_id'] = $item['service'];
            } else {
                $assign[$key]['staff_id']   = $item['staff'];
                $assign[$key]['service_id'] = $item['service'];
            }
        }

        $data['assign']      = json_encode($assign);
        $data['service_ids'] = json_encode($service_ids);
        unset($data['assign_more']);
        $data['time'] = formatDate($data['time'], 'Y-m-d H:i');
        if (isset($data['end_time'])) {
            $data['end_time'] = formatDate($data['end_time'], 'Y-m-d H:i');
        }

        $data['room_id']       = json_encode($data['room_id'] ?? []);
        $data['instrument_id'] = json_encode($data['instrument_id'] ?? []);
        $book->update($data);
        $book->staffs()->sync($assign_more);
        foreach($assign_more as $staff_id) {
            $book->addNotification($staff_id);
        }
        NotificationModel::query()
                         ->where('data->appointment_id', $book->id)
                         ->where('notifiable_id', '<>', $book->user_id)
                         ->whereNotIn('notifiable_id', ($assign_more ?? []))
                         ->delete();

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
        try {
            $appointment->update($data);
            return [
                'status'    => 200,
                'past_time' => (strtotime($appointment->time) < time())
            ];
        } catch(Exception $e) {
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

        $member = Member::find($member_id);
        if (empty($member)) {
            $request->session()->flash('danger', trans("This client has been removed from the system"));
            return redirect()->back();
        }

        /** Check appointment of member progressing */
        $check_appointment_progressing->where('status', Appointment::PROGRESSING_STATUS)
                                      ->where('member_id', $member_id)
                                      ->where('id', '<>', $id);
        $check_appointment_progressing = $check_appointment_progressing->first();
        if (!empty($check_appointment_progressing)) {
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
        if (!empty($check_user_progressing)) {
            $request->session()->flash('error', trans("Staff of this appointment is in progress."));

            return redirect()->route('get.member_service.add', $check_user_progressing->member_id);
        }

        /** Check In */
        if ($appointment->status !== Appointment::PROGRESSING_STATUS) {
            $appointment->status     = Appointment::PROGRESSING_STATUS;
            $appointment->start_time = formatDate(time(), 'Y-m-d H:i:s');
            $appointment->save();
        }

        $request->session()->flash('success', trans("This appointment in progress."));

        if ($appointment->type === Appointment::COURSE_TYPE) {
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

        if ($appointment->member->checkServiceInProgressing()) {
            $request->session()->flash('error', trans("There are services in progress."));

            return redirect()->back();
        }
        if ($appointment->member->checkCourseInProgressing()) {
            $request->session()->flash('error', trans("There are courses in progress."));

            return redirect()->back();
        }
        $appointment->status   = Appointment::COMPLETED_STATUS;
        $appointment->end_time = formatDate(time(), 'Y-m-d H:i:s');
        $appointment->save();
        $request->session()->flash('success', trans("This appointment is completed."));

        return redirect()->route('get.member.appointment', [$appointment->member_id, 'type' => $appointment->type]);
    }

    /**
     * @param $id
     * @return array|string
     */
    public function getProductList($appointment){
        $title       = ($appointment->member->name ?? "N/A") . ' | ' . ($appointment->member->phone ?? "N/A");
        $assign_more = !empty($appointment->staffs->count()) ?
            ", " . implode(', ', $appointment->staffs->pluck('name')->toArray()) : null;
        $html        = '<div class="table-responsive"><div>';
        $html        .= '<h5>' . $title . '</h5>';
        $html        .= '<label>' . trans('Staff') . ':</label> ' . ($appointment->user->name ?? "N/A") . $assign_more;
        $html        .= '<div class="form-group">';
        $html        .= '<label>' . trans('Total Intend Time: ') . '</label>';
        $html        .= '<span class="text-danger">' . ($appointment->getTotalIntendTimeService() ?? 0) . '</span> ' .
                        trans(' minutes');
        $html        .= ' (' . trans("End time") . ': <span class="text-danger">'
                        . formatDate(strtotime($appointment->time) +
                                     (($appointment->getTotalIntendTimeService()) * 60), 'H:i') .
                        '</span>)';
        $html        .= '</div></div>';

        $html .= '<table class="table table-striped" id="product-list">';

        $assign = $appointment->getAssign();
        if (!empty($assign)) {
            $html .= '<thead>
                            <tr>
                                <th>' . trans('Service') . '</th>
                                <th>' . trans('Staff') . '</th>
                                <th>' . trans('End time') . '</th>
                                <th>' . trans('Intend Time') . '</th>
                            </tr>
                        </thead>';
            $html .= '<tbody>';
            unset($assign['check']);
            foreach($assign as $item) {
                $service_null = empty($item['service_id']) ? 'font-italic' : null;
                $html         .= '<tr class="pl-2">
                        <td>
                            <span class="text-option ' . $service_null . '">
                                    ' . $item['service_name'] . '
                            </span>
                        </td>
                        <td>
                            <span class="text-option">' . $item['staff_name'] . '</span>
                        </td>
                        <td>
                            <span class="text-option">' . $item['time'] . '</span>
                        </td>
                        <td>
                            <span class="text-option">' . $item['intend_time'] . " " . trans("minutes") . '</span>
                        </td>
                    </tr>';
            }
        } else {
            $html .= '<thead>
                            <tr >
                                <th > ' . trans('Service') . ' </th >
                                <th > ' . trans('Intend Time') . ' </th >
                            </tr >
                        </thead > ';
            $html .= '<tbody > ';
            foreach($appointment->service_ids as $item) {
                if (!empty($item)) {
                    $html .= '<tr class="pl-2" >
                            <td >
                                <span class="text-option" > ' . $item->name . '</span >
                            </td >
                            <td >
                                <span class="text-option" > ' . $item->intend_time . " " . trans(" minutes") . '</span >
                            </td >
                        </tr > ';
                }
            }
        }
        $html .= '</tbody ></table ></div > ';


        return $html;
    }
}
