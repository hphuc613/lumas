<?php

namespace Modules\Room\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Modules\Base\Model\Status;
use Modules\Room\Http\Requests\RoomRequest;
use Modules\Room\Model\Room;


class RoomController extends Controller{

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
        $filter   = $request->all();
        $rooms    = Room::filter($filter)->paginate(50);
        $statuses = Status::getStatuses();
        if (Config::get('app.locale') == 'zh-TW') {
            $statuses = [
                1  => "可用",
                -1 => "不可用"
            ];
        }

        return view('Room::index', compact('rooms', 'filter', 'statuses'));
    }


    /**
     * @param Request $request
     * @return array|string
     */
    public function getCreate(Request $request){
        $statuses = Status::getStatuses();
        if (Config::get('app.locale') == 'zh-TW') {
            $statuses = [
                1  => "可用",
                -1 => "不可用"
            ];
        }

        if (!$request->ajax()) {
            return redirect()->back();
        }

        return $this->renderAjax('Room::form', compact('statuses'));
    }

    /**
     * @param RoomRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(RoomRequest $request){
        $room = new Room($request->all());
        $room->save();
        $request->session()->flash('success', trans('Room created successfully.'));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUpdate(Request $request, $id){
        $room     = Room::find($id);
        $statuses = Status::getStatuses();
        if (Config::get('app.locale') == 'zh-TW') {
            $statuses = [
                1  => "可用",
                -1 => "不可用"
            ];
        }

        return view('Room::form', compact('room', 'statuses'));
    }

    /**
     * @param RoomRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdate(RoomRequest $request, $id){
        $room = Room::find($id);
        $room->update($request->all());
        $request->session()->flash('success', trans('Room updated successfully.'));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $id){
        $room = Room::find($id);
        $room->delete();
        $request->session()->flash('success', trans('Room deleted successfully.'));

        return back();
    }
}
