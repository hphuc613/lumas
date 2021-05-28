<?php

namespace Modules\Store\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Base\Model\Status;
use Modules\Store\Http\Requests\StoreRequest;
use Modules\Store\Model\Store;


class StoreController extends Controller{

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
        $filter = $request->all();
        $stores = Store::filter($filter)->paginate(20);

        return view("Store::index", compact('stores', 'filter'));
    }

    /**
     * @return Application|Factory|View
     */
    public function getCreate(){
        $statuses = Status::getStatuses();

        return view("Store::create", compact('statuses'));
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function postCreate(StoreRequest $request){
        $store = new Store($request->all());
        $store->save();
        $request->session()->flash('success', 'Store created successfully');

        return redirect()->route('get.store.list');
    }

    /**
     * @return Application|Factory|View
     */
    public function getUpdate($id){
        $statuses = Status::getStatuses();
        $store    = Store::find($id);

        return view("Store::update", compact('statuses', 'store'));
    }

    /**
     * @param StoreRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function postUpdate(StoreRequest $request, $id){
        $store = Store::find($id);
        $store->update($request->all());
        $request->session()->flash('success', 'Store updated successfully');

        return redirect()->route('get.store.list');
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function getDelete(Request $request, $id){
        $store = Store::find($id);
        $store->deleted();
        $request->session()->flash('success', 'Store deleted successfully');

        return redirect()->back();
    }
}
