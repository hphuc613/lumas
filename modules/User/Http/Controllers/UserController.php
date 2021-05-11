<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Base\Model\Status;
use Modules\Role\Model\Role;
use Modules\User\Http\Requests\UserValidation;
use Modules\User\Model\User;

class UserController extends Controller {

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        # parent::__construct();
    }

    /**
     * @return Factory|View
     */
    public function index(Request $request) {
        $filter   = $request->all();
        $users    = User::filter($filter)->paginate(20);
        $statuses = Status::STATUSES;

        return view('User::index', compact('users', 'statuses', 'filter'));
    }

    /**
     * @return Factory|View
     */
    public function getCreate() {
        $roles    = Role::getArray();
        $statuses = Status::STATUSES;

        return view('User::create', compact('roles', 'statuses'));
    }

    /**
     * @param UserValidation $request
     *
     * @return RedirectResponse
     */
    public function postCreate(UserValidation $request) {
        if(!empty($request->all()) && $request->password === $request->password_re_enter) {
            $data = $request->all();
            unset($data['password_re_enter']);
            unset($data['role_id']);
            $user = new User($data);
            $user->save();
            $request->session()->flash('success', trans('User created successfully.'));
        }

        return redirect()->route('get.user.list');
    }

    /**
     * @param $id
     *
     * @return Factory|View
     */
    public function getUpdate($id) {
        $roles    = Role::getArray();
        $user     = User::find($id);
        $statuses = Status::STATUSES;

        return view('User::update', compact('roles', 'user', 'statuses'));
    }

    /**
     * @param UserValidation $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function postUpdate(UserValidation $request, $id) {
        $data = $request->all();
        $user = User::find($id);
        if(empty($data['password'])) {
            unset($data['password']);
        }
        unset($data['password_re_enter']);
        $user->update($data);
        $request->session()->flash('success', trans('User updated successfully.'));

        return redirect()->route('get.user.list');
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function postUpdateStatus(Request $request) {
        $data = $request->all();
        if($data != null) {
            $user = User::find($data['id']);
            if($user) {
                $user->status = $data['status'];
                $user->save();
                $request->session()->flash('success', trans('User updated successfully.'));
            }
        }
        return true;
    }

    /**
     * @return Factory|View
     */
    public function getProfile() {
        $id       = Auth::guard()->id();
        $roles    = Role::getArray();
        $user     = User::find($id);
        $statuses = Status::STATUSES;

        return view('User::update', compact('roles', 'user', 'statuses'));
    }

    /**
     * @param UserValidation $request
     *
     * @return RedirectResponse
     */
    public function postProfile(UserValidation $request) {
        $id   = Auth::guard()->id();
        $data = $request->all();
        $user = User::find($id);
        if(empty($data['password'])) {
            unset($data['password']);
        }
        unset($data['password_re_enter']);
        $user->update($data);
        $request->session()->flash('success', trans('User updated successfully.'));

        return redirect()->route('dashboard');
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, $id) {
        $user = User::find($id);
        $user->delete();
        $request->session()->flash('success', trans('User deleted successfully.'));

        return back();
    }

}
