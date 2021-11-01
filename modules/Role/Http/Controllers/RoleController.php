<?php

namespace Modules\Role\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Base\Model\Status;
use Modules\Role\Http\Requests\RoleValidation;
use Modules\Role\Model\CommissionRate;
use Modules\Role\Model\Permission;
use Modules\Role\Model\Role;

class RoleController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    public function index(Request $request){
        $filter = $request->all();
        $roles  = Role::filter($filter)->paginate(50);

        return view('Role::index', compact('roles', 'filter'));
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function getCreate(Request $request){
        $statuses = Status::getStatuses();

        if(!$request->ajax()){
            return redirect()->back();
        }

        return $this->renderAjax('Role::form', compact('statuses'));
    }

    /**
     * @param RoleValidation $request
     * @return RedirectResponse
     */
    public function postCreate(RoleValidation $request){
        $role = new Role($request->all());
        $role->save();
        $request->session()->flash('success', trans('Role created successfully.'));

        return redirect()->route('get.role.update', $role->id);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function getUpdate(Request $request, $id){
        $role     = Role::find($id);
        $statuses = Status::getStatuses();

        $rates = CommissionRate::query()->where('role_id', $role->id)->paginate(15);
        return view('Role::update', compact('role', 'statuses', 'rates'));
    }

    /**
     * @param RoleValidation $request
     * @param $id
     * @return RedirectResponse
     */
    public function postUpdate(RoleValidation $request, $id){
        $role = Role::find($id);
        $role->update($request->all());
        $request->session()->flash('success', trans('Role updated successfully.'));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function delete(Request $request, $id){
        $role = Role::find($id);
        if($role->checkUserHasRole()){
            $request->session()->flash('error', trans('This role cannot delete because has users belongs this role'));
        }else{
            $role->delete();
            $request->session()->flash('success', trans('Role deleted successfully.'));
        }

        return back();
    }
}
