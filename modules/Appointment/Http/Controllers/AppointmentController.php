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
use Modules\Service\Model\Service;
use Modules\Service\Model\ServiceType;
use Modules\Store\Model\Store;

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
        $appointments = Appointment::where('user_id', Auth::id())->get();
        $events       = [];
        foreach($appointments as $appointment){
            $events[] = [
                'id'    => $appointment->id,
                'title' => $appointment->name,
                'start' => Carbon::parse($appointment->time)->format('Y-m-d H:i'),
                'color' => (strtotime($appointment->time) < time()) ? 'red' : 'blue'
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

        if(!$request->ajax()){
            return redirect()->back();
        }
        return view("Appointment::form", compact('statuses', 'service_types', 'clients', 'stores'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function postCreate(AppointmentRequest $request){
        $data            = $request->all();
        $data['user_id'] = Auth::id();
        $data['time']    = Carbon::parse($data['time'])->format('Y-m-d H:i');
        $book            = new Appointment($data);
        $book->save();
        $request->session()->flash('success', 'Appointment booked successfully.');

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
        $appointment       = Appointment::find($id);
        $appointment->time = Carbon::parse($appointment->time)->format('d-m-Y H:i');

        if(!$request->ajax()){
            return redirect()->back();
        }

        return view("Appointment::form", compact('statuses', 'service_types', 'clients', 'stores', 'appointment'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function postUpdate(AppointmentRequest $request, $id){
        $data         = $request->all();
        $data['time'] = Carbon::parse($data['time'])->format('Y-m-d H:i');
        $book         = Appointment::find($id);
        $book->update($data);

        $request->session()->flash('success', 'Appointment updated successfully.');

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function delete(Request $request, $id){
        $appointment = Appointment::find($id);
        $appointment->delete();
        $request->session()->flash('success', 'Appointment deleted successfully.');

        return redirect()->route('get.appointment.list');
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|int[]
     */
    public function postChangeTime(Request $request, $id){
        $data         = $request->all();
        $data['time'] = Carbon::parse($data['time'])->format('Y-m-d H:i');
        $book         = Appointment::find($id);
        try{
            $book->update($data);
            return ['status' => 200];
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

        $services = Service::where('status', Status::STATUS_ACTIVE)->where('type_id', $id)->orderBy('name',
                                                                                                    'asc')->pluck('name',
                                                                                                                  'id')->toArray();
        $html     = "<option value=''>Select</option>";
        foreach($services as $key => $service){
            $html .= "<option value='$key'>$service</option>";
        }

        return $html;
    }

}
