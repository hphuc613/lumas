<?php

namespace Modules\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Appointment\Model\Appointment;
use Modules\Base\Model\Status;
use Modules\Course\Model\Course;
use Modules\Member\Http\Requests\MemberCourseRequest;
use Modules\Member\Model\Member;
use Modules\Member\Model\MemberCourse;
use Modules\Member\Model\MemberCourseHistory;
use Modules\Voucher\Model\CourseVoucher;


class MemberCourseController extends Controller{

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
     * @param $id
     * @return Application|Factory|View
     */
    public function getAdd(Request $request, $id){
        $filter               = $request->all();
        $member               = Member::find($id);
        $courses              = Course::getArray(Status::STATUS_ACTIVE);
        $query_member_courses = new MemberCourse();

        /**
         * Get list course
         * @var  $member_courses
         */
        $member_courses = clone $query_member_courses;
        $member_courses = $member_courses->filter($filter, $member->id)
                                         ->where('status', MemberCourse::COMPLETED_STATUS)
                                         ->paginate(5, ['*'], 'course_page');

        /**
         * Get list service completed
         * @var  $completed_member_services
         */
        $completed_member_courses = clone $query_member_courses;
        $completed_member_courses = $completed_member_courses->filterCompleted($filter, $member->id)
                                                             ->paginate(5, ['*'], 'course_completed_page');

        /**
         * Get list using service
         * @var  $progressing_services
         */
        $progressing_courses = clone $query_member_courses;
        $progressing_courses = $progressing_courses->query()
                                                   ->where('member_id', $member->id)
                                                   ->where('status', MemberCourse::PROGRESSING_STATUS)
                                                   ->get();

        $search_courses           = MemberCourse::getArrayByMember($member->id);
        $search_completed_courses = MemberCourse::getArrayByMember($member->id, 1);
        $histories                = MemberCourseHistory::filter($filter, $member->id)
                                                       ->paginate(10, ['*'], 'history_page');

        return view('Member::backend.member_course.index',
            compact('member', 'courses', 'member_courses', 'filter', 'completed_member_courses', 'search_courses', 'search_completed_courses', 'progressing_courses', 'histories'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function getView(Request $request, $id){
        $filter               = $request->all();
        $query_member_courses = new MemberCourse();


        $member_course = clone $query_member_courses;
        $member_course = $member_course->find($id);

        /**
         * Get list courses
         * @var  $member_services
         */
        $member_courses = clone $query_member_courses;
        $member_courses = $member_courses->filter($filter, $member_course->member_id)
                                         ->paginate(5, ['*'], 'course_page');

        /**
         * Get list courses completed
         * @var  $completed_member_courses
         */
        $completed_member_courses = clone $query_member_courses;
        $completed_member_courses = $completed_member_courses->filterCompleted($filter, $member_course->member_id)
                                                             ->paginate(5, ['*'], 'course_completed_page');

        $member   = Member::find($member_course->member_id);
        $courses  = Course::getArray();
        $vouchers = CourseVoucher::query()
                                 ->where('course_id', $member_course->course_id)
                                 ->pluck('code', 'id')
                                 ->toArray();

        $histories = MemberCourseHistory::filter($filter, $member->id, $member_course->course_id)
                                        ->where('member_course_id', $member_course->id)
                                        ->paginate(10, ['*'], 'history_page');

        /**
         * Get list using service
         * @var  $progressing_courses
         */
        $progressing_courses = clone $query_member_courses;
        $progressing_courses = $progressing_courses->query()
                                                   ->where('member_id', $member->id)
                                                   ->where('status', MemberCourse::PROGRESSING_STATUS)
                                                   ->get();

        $statuses                 = MemberCourse::getStatus();
        $search_courses           = MemberCourse::getArrayByMember($member->id);
        $search_completed_courses = MemberCourse::getArrayByMember($member->id, 1);
        return view('Member::backend.member_course.index',
            compact('member', 'courses', 'member_courses', 'member_course', 'vouchers', 'histories',
                'completed_member_courses', 'filter', 'statuses', 'search_courses', 'search_completed_courses', 'progressing_courses'));
    }

    /**
     * @param MemberCourseRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function postEdit(MemberCourseRequest $request, $id){
        $data          = $request->all();
        $member_course = MemberCourse::find($id);

        /** Check when no Voucher */
        $check_no_voucher = empty($member_course->voucher_id)
            && (int)$member_course->price !== (int)$member_course->course->price;

        /** Check when has Voucher */
        $check_has_voucher = false;
        if(!empty($member_course->voucher_id)){
            $voucher              = $member_course->voucher;
            $check_active_voucher =
                (!empty($voucher->end_at) && strtotime($voucher->end_at) < strtotime(Carbon::today()))
                || $voucher->status !== Status::STATUS_ACTIVE; //Check Voucher Inactive
            $check_has_voucher    = !empty($member_course->voucher_id) && $check_active_voucher;
        }

        if($check_no_voucher || $check_has_voucher){
            $request->session()
                    ->flash('error', trans("Cannot updated! It seems the price of this course or voucher is too old. Please create a new one."));
            return redirect()->back();
        }

        $data['quantity'] = (int)$member_course->quantity + (int)$data['add_more_quantity'];
        unset($data['add_more_quantity']);
        $member_course->update($data);

        $request->session()->flash('success', trans("Course edited successfully."));
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function delete(Request $request, $id){
        $member_course = MemberCourse::find($id);
        $member_id     = $member_course->member_id;
        $member_course->delete();

        $request->session()->flash('success', trans("Course deleted successfully."));
        return redirect()->route('get.member_course.add', $member_id);
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|RedirectResponse|string
     */
    public function eSign(Request $request, $id){
        $member_course = MemberCourse::find($id);

        if($request->post()){
            $data        = $request->all();
            $appointment = Appointment::query()
                                      ->where('member_id', $member_course->member_id)
                                      ->where('status', Appointment::PROGRESSING_STATUS)
                                      ->first();

            /** E-sign*/
            $member_course->eSign($data, $appointment);

            /**  Reduce the quantity of */
            $member_course->deduct_quantity += 1;
            $member_course->status          = MemberCourse::COMPLETED_STATUS;
            $member_course->save();

            $request->session()->flash('success', trans("Signed successfully."));
            return redirect()->back();
        }

        if(!$request->ajax()){
            return redirect()->back();
        }

        return $this->renderAjax('Member::backend.member_course.e_sign', compact('member_course'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function intoProgress(Request $request, $id){
        $member_course = MemberCourse::find($id);
        if(!$member_course->member->getAppointmentInProgressing()){
            $request->session()->flash('error', trans("Please check in an appointment."));

            return redirect()->back();
        }
        $member_course->status     = MemberCourse::PROGRESSING_STATUS;
        $member_course->updated_by = Auth::id();
        $member_course->save();
        $request->session()->flash('success', trans("Client using this course."));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function outProgress(Request $request, $id){
        $member_course         = MemberCourse::find($id);
        $member_course->status = MemberCourse::COMPLETED_STATUS;
        $member_course->save();
        $request->session()->flash('success', trans("Course has been removed from Course progressing list."));

        return redirect()->back();
    }
}
