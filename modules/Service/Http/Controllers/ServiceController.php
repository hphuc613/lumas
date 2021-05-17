<?php

namespace Modules\Service\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Base\Model\Status;
use Modules\Service\Http\Requests\ServiceRequest;
use Modules\Service\Model\Service;
use Modules\Service\Model\ServiceType;


class ServiceController extends Controller{

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
        $services = Service::paginate(20);
        return view("Service::service.index", compact('services'));
    }

    /**
     * @return Application|Factory|View
     */
    public function getcreate(){
        $statuses      = Status::STATUSES;
        $service_types = ServiceType::getArray();
        return view("Service::service.create", compact('statuses', 'service_types'));
    }

    /**
     * @param ServiceRequest $request
     * @return RedirectResponse
     */
    public function postCreate(ServiceRequest $request){
        $service = new Service($request->all());
        $service->save();

        $request->session()->flash('success', 'Service created successfully');
        return redirect()->route('get.service.list');
    }

    /**
     * @return Application|Factory|View
     */
    public function getUpdate($id){
        $service       = Service::find($id);
        $statuses      = Status::STATUSES;
        $service_types = ServiceType::getArray();
        return view("Service::service.update", compact('service', 'statuses', 'service_types'));
    }

    /**
     * @param ServiceRequest $request
     * @return RedirectResponse
     */
    public function postUpdate(ServiceRequest $request, $id){
        $service = Service::find($id);
        $service->update($request->all());

        $request->session()->flash('success', 'Service updated successfully');
        return redirect()->route('get.service.list');
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function delete(Request $request, $id){
        $service = Service::find($id);
        $service->delete();
        $request->session()->flash('success', trans('Service deleted successfully.'));

        return back();
    }
}