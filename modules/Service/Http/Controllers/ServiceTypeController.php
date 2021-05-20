<?php

namespace Modules\Service\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Base\Model\Status;
use Modules\Service\Http\Requests\ServiceTypeRequest;
use Modules\Service\Model\ServiceType;


class ServiceTypeController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }


    public function index(Request $request){
        $filter        = $request->all();
        $service_types = ServiceType::filter($filter)->paginate(20);

        return view('Service::service_type.index', compact('service_types', 'filter'));
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function getCreate(Request $request){
        $statuses = Status::STATUSES;

        if(!$request->ajax()){
            return redirect()->back();
        }

        return $this->renderAjax('Service::service_type.form', compact('statuses'));
    }

    /**
     * @param ServiceTypeRequest $request
     * @return RedirectResponse
     */
    public function postCreate(ServiceTypeRequest $request){
        $service_type = new ServiceType($request->all());
        $service_type->save();
        $request->session()->flash('success', trans('Service type created successfully.'));

        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|RedirectResponse|string
     */
    public function getUpdate(Request $request, $id){
        $service_type = ServiceType::find($id);
        $statuses     = Status::STATUSES;
        if(!$request->ajax()){
            return redirect()->back();
        }
        return $this->renderAjax('Service::service_type.form', compact('service_type', 'statuses'));
    }

    /**
     * @param ServiceTypeRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function postUpdate(ServiceTypeRequest $request, $id){
        $service_type = ServiceType::find($id);
        $service_type->update($request->all());
        $request->session()->flash('success', trans('Service type updated successfully.'));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function delete(Request $request, $id){
        $service_type = ServiceType::find($id);
        if($service_type->services->isEmpty()){
            $service_type->delete();
            $request->session()->flash('success', trans('Service type deleted successfully.'));
        }else{
            $request->session()->flash('error',
                                       trans('This service type cannot delete because has active services belongs this type'));
        }

        return back();
    }
}
