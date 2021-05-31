<?php

namespace Modules\Member\Http\Controllers;

use App\AppHelpers\Excel\Export;
use App\AppHelpers\Excel\Import;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Base\Model\Status;
use Modules\Member\Http\Requests\MemberRequest;
use Modules\Member\Model\Member;
use Modules\Member\Model\MemberType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class MemberController extends Controller{

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
        $filter       = $request->all();
        $statuses     = Status::getStatuses();
        $member_types = MemberType::getArray();
        $members      = Member::filter($filter)->orderBy('name')->paginate(15);
        return view("Member::backend.member.index", compact('members', 'filter', 'member_types', 'statuses'));
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function getCreate(){
        $statuses     = Status::getStatuses();
        $member_types = MemberType::getArray();
        return view('Member::backend.member.create', compact('member_types', 'statuses'));
    }

    /**
     * @param MemberRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function postCreate(MemberRequest $request){
        $data             = $request->all();
        $data['birthday'] = Carbon::parse($data['birthday'])->format('Y-m-d');
        $member           = new Member($data);
        $member->save();
        $request->session()->flash('success', trans('Client updated successfully.'));

        return redirect()->route('get.member.list');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function getUpdate($id){
        $member       = Member::find($id);
        $statuses     = Status::getStatuses();
        $member_types = MemberType::getArray();
        return view('Member::backend.member.update', compact('member', 'member_types', 'statuses'));
    }

    /**
     * @param MemberRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function postUpdate(MemberRequest $request, $id){
        if($request->post()){
            $data   = $request->all();
            $member = Member::find($id);
            if(empty($data['password'])){
                unset($data['password']);
            }
            unset($data['password_re_enter']);
            $data['birthday'] = Carbon::parse($data['birthday'])->format('Y-m-d');
            $member->update($data);
            $request->session()->flash('success', trans('Client updated successfully.'));
        }

        return redirect()->route('get.member.list');
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function postUpdateStatus(Request $request){
        $data = $request->all();
        if($data != null){
            $member = Member::find($data['id']);
            if($member){
                $member->status = $data['status'];
                $member->save();
                $request->session()->flash('success', trans('Client updated successfully.'));
            }
        }
        return true;
    }

    /**
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     */
    public function getImport(Request $request){
        if(!$request->ajax()){
            return redirect()->back();
        }
        return view('Member::backend.member.import');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|BinaryFileResponse
     */
    public function postImport(Request $request){
        if($request->has('file')){
            $file = $request->file;
            /** Get array data*/
            $array = Excel::toArray(new Import, $file);
            $array = reset($array);

            /** Get header*/
            $header = $array[0];
            /** Get data*/
            unset($array[0]);
            $clients = $array;


            $error_data = [];
            $i          = 1;
            foreach($clients as $key => $client){
                $data            = array_combine($header, $client);
                $data['type_id'] = 1;
                $rule            = new MemberRequest;
                $validator       = Validator::make($data, $rule->rules(), $rule->messages(), $rule->attributes());
                $messages        = $validator->getMessageBag()->toArray();
                if(!empty($messages)){
                    $data["#"] = $i;
                    $i++;
                    $data['error_messages'] = '';
                    foreach($messages as $message){
                        $data['error_messages'] .= implode(" ", $message) . " ";
                    }
                    unset($data['type_id']);
                    $error_data[] = $data;
                    continue;
                }else{
                    unset($data["#"]); // leave column number
                    $member = new Member($data);
                    $member->save();
                }
            }

            if(!empty($error_data)){
                array_push($header, 'error_messages');
                $export = new Export;
                /*$export->collection = Member::all();
                $export->headings = array_keys(Member::first()->toArray());*/
                $export->collection = collect($error_data);
                $export->headings   = $header;
                return Excel::download($export, 'client_fail_import.xlsx');
            }
        }
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function delete(Request $request, $id){
        $member = Member::find($id);
        $member->delete();
        $request->session()->flash('success', trans('Client deleted successfully.'));

        return redirect()->back();
    }
}
