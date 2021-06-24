<?php

namespace Modules\Role\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Role\Http\Requests\CommissionRateRequest;
use Modules\Role\Model\CommissionRate;
use Modules\Role\Model\Permission;
use Modules\Role\Model\Role;

class CommissionRateController extends Controller{

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
     * @return array|string
     */
    public function getCreate(Request $request, $role_id){
        if(!$request->ajax()){
            return redirect()->back();
        }

        $role = Role::find($role_id);

        return $this->renderAjax('Role::commission_rate.form', compact('role'));
    }

    /**
     * @param CommissionRateRequest $request
     * @param $role_id
     * @return RedirectResponse
     */
    public function postCreate(CommissionRateRequest $request, $role_id){
        $rate = new CommissionRate($request->all());
        $rate->save();
        $request->session()->flash('success', trans('Commission Rate created successfully.'));

        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|RedirectResponse|string
     */
    public function getUpdate(Request $request, $id){
        if(!$request->ajax()){
            return redirect()->back();
        }
        $rate = CommissionRate::find($id);
        return $this->renderAjax('Role::commission_rate.form', compact('rate'));
    }

    /**
     * @param CommissionRateRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function postUpdate(CommissionRateRequest $request, $id){
        $rate = CommissionRate::find($id);
        $rate->update($request->all());
        $request->session()->flash('success', trans('Commission Rate updated successfully.'));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function delete(Request $request, $id){
        $rate = CommissionRate::find($id);
        $rate->delete();
        $request->session()->flash('success', trans('Role deleted successfully.'));

        return back();
    }
}
