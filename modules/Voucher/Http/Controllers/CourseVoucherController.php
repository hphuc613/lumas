<?php

namespace Modules\Voucher\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Base\Model\Status;
use Modules\Course\Model\Course;
use Modules\Voucher\Http\Requests\CourseVoucherRequest;
use Modules\Voucher\Model\CourseVoucher;


class CourseVoucherController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    public function index(Request $request){
        $filter   = $request->all();
        $courses  = Course::getArray(Status::STATUS_ACTIVE);
        $vouchers = CourseVoucher::filter($filter)
                                 ->WhereHas('course', function($qc){
                                     $qc->where('status', Status::STATUS_ACTIVE);
                                 })
                                 ->orderBy('start_at', 'DESC')
                                 ->paginate(15);

        return view("Voucher::course_voucher.index", compact('vouchers', 'courses', 'filter'));
    }

    /**
     * @return Application|Factory|View
     */
    public function getCreate(){
        $statuses = Status::getStatuses();
        $courses  = Course::getArray(Status::STATUS_ACTIVE);
        return view("Voucher::course_voucher.create", compact('statuses', 'courses'));
    }

    /**
     * @param CourseVoucherRequest $request
     * @return RedirectResponse
     */
    public function postCreate(CourseVoucherRequest $request){
        $data             = $request->all();
        $data['start_at'] = formatDate(strtotime($data['start_at']), 'Y-m-d H:i:s');
        $data['end_at']   = (!empty($data['end_at'])) ? formatDate($data['end_at'], 'Y-m-d') : null;
        $voucher          = new CourseVoucher($data);
        $voucher->save();

        $request->session()->flash('success', trans('Voucher created successfully.'));
        return redirect()->route('get.course_voucher.list');
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function getCreatePopUp(Request $request){
        $statuses = Status::getStatuses();
        $courses  = Course::getArray(Status::STATUS_ACTIVE);

        if(!$request->ajax()){
            return redirect()->back();
        }

        return view("Voucher::course_voucher._form", compact('statuses', 'courses'));
    }

    /**
     * @param CourseVoucherRequest $request
     * @return RedirectResponse
     */
    public function postCreatePopUp(CourseVoucherRequest $request){
        $this->postCreate($request);

        $request->session()->flash('success', trans('Voucher created successfully.'));
        return redirect()->back();
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function getUpdate($id){
        $statuses = Status::getStatuses();
        $voucher  = CourseVoucher::find($id);
        $courses  = Course::getArray(Status::STATUS_ACTIVE);

        return view("Voucher::course_voucher.update", compact('statuses', 'voucher', 'courses'));
    }

    /**
     * @param CourseVoucherRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function postUpdate(CourseVoucherRequest $request, $id){
        $data             = $request->all();
        $data['start_at'] = formatDate(strtotime($data['start_at']), 'Y-m-d H:i:s');
        $data['end_at']   = (!empty($data['end_at'])) ? formatDate($data['end_at'], 'Y-m-d') : null;
        $voucher          = CourseVoucher::find($id);
        $voucher->update($data);
        $voucher->save();

        $request->session()->flash('success', trans('Voucher updated successfully.'));
        return redirect()->route('get.course_voucher.list');
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function delete(Request $request, $id){
        $voucher = CourseVoucher::find($id);
        $voucher->delete();

        $request->session()->flash('success', trans('Voucher deleted successfully.'));
        return redirect()->route('get.course_voucher.list');
    }

    /**
     * @param $id
     * @return string
     */
    public function getListVoucherByCourseID($id){
        $vouchers = CourseVoucher::where('status', Status::STATUS_ACTIVE)
                                 ->where('course_id', $id)
                                 ->orderBy('start_at', 'desc')
                                 ->get();

        $html = "<option value=''>Select</option>";
        foreach($vouchers as $voucher){
            $text = $voucher->code . ' | ' . $voucher->price;
            if(!empty($voucher->end_at)){
                if(strtotime($voucher->end_at) >= strtotime(Carbon::today())){
                    $html .= "<option value='$voucher->id'>$text</option>";
                }
            }else{
                $html .= "<option value='$voucher->id'>$text</option>";
            }
        }

        return $html;
    }
}
