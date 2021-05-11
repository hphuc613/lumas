<?php

namespace Modules\Role\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Base\Model\Status;
use Modules\Role\Http\Requests\RoleValidation;
use Modules\Role\Model\Permission;
use Modules\Role\Model\Role;

class RoleController extends Controller {

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        # parent::__construct();
    }

    public function index(Request $request){
        $filter = $request->all();
        $roles  = Role::filter($filter)->paginate(20);

        return view('Role::index', compact('roles', 'filter'));
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

        return $this->renderAjax('Role::form', compact('statuses'));
    }

    public function postCreate(RoleValidation $request){
        $role = new Role($request->all());
        $role->save();
        $request->session()->flash('success', trans('Role created successfully.'));

        return back();
    }

    public function getUpdate(Request $request, $id){
        $role     = Role::find($id);
        $statuses = Status::STATUSES;
        if(!$request->ajax()){
            return redirect()->back();
        }
        return $this->renderAjax('Role::form', compact('role', 'statuses'));
    }

    public function postUpdate(RoleValidation $request, $id) {
        $role = Role::find($id);
        $role->update($request->all());
        $request->session()->flash('success', trans('Role updated successfully.'));

        return redirect()->back();
    }

    public function delete(Request $request, $id) {
        $role = Role::find($id);
        if($role->checkUserHasRole()) {
            $role->delete();
            $request->session()->flash('success', trans('Role deleted successfully.'));
        } else {
            $request->session()->flash('error', trans('Role cannot delete because has user belongs this role'));
        }

        return back();
    }
}
