<?php

namespace Modules\Instrument\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Base\Model\Status;
use Modules\Instrument\Http\Requests\InstrumentRequest;
use Modules\Instrument\Model\Instrument;


class InstrumentController extends Controller{

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $filter      = $request->all();
        $instruments = Instrument::filter($filter)->paginate(15);

        return view('Instrument::index', compact('instruments', 'filter'));
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function getCreate(Request $request){
        $statuses = Status::getStatuses();

        if (!$request->ajax()) {
            return redirect()->back();
        }

        return $this->renderAjax('Instrument::form', compact('statuses'));
    }

    /**
     * @param InstrumentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(InstrumentRequest $request){
        $instrument = new Instrument($request->all());
        $instrument->save();
        $request->session()->flash('success', trans('Instrument created successfully.'));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUpdate(Request $request, $id){
        $instrument = Instrument::find($id);
        $statuses   = Status::getStatuses();

        return view('Instrument::form', compact('instrument', 'statuses'));
    }

    /**
     * @param InstrumentRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdate(InstrumentRequest $request, $id){
        $instrument = Instrument::find($id);
        $instrument->update($request->all());
        $request->session()->flash('success', trans('Instrument updated successfully.'));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $id){
        $instrument = Instrument::find($id);
        $instrument->delete();
        $request->session()->flash('success', trans('Instrument deleted successfully.'));

        return back();
    }
}
