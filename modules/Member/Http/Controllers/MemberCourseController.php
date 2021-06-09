<?php

namespace Modules\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use ErrorException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Appointment\Model\Appointment;
use Modules\Base\Model\Status;
use Modules\Course\Model\Course;
use Modules\Member\Http\Requests\MemberCourseRequest;
use Modules\Member\Http\Requests\MemberServiceRequest;
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
     * @param MemberServiceRequest $request
     * @param $id
     * @return RedirectResponse
     * @throws ErrorException
     */
    public function postAdd(MemberCourseRequest $request, $id){
        $data                      = $request->all();
        $member                    = Member::find($id);
        $course                    = Course::where('id', $data['course_id'])->first();
        $member_course_check_exist = MemberCourse::query()->where('course_id', $course->id)
                                                 ->where('member_id', $member->id)
                                                 ->where('voucher_id', $request->voucher_id)
                                                 ->first();
        if(!empty($member_course_check_exist)){
            $request->session()->flash('error', trans("This course has not been used yet. Update right here."));
            return redirect()->route("get.member_course.edit", $member_course_check_exist->id);
        }

        $member_course       = new MemberCourse($data);
        $member_course->code = $member_course->generateCode();
        $member_course->save();
        $request->session()->flash('success', trans("Course added successfully."));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function getEdit(Request $request, $id){
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
        $courses  = Course::getArray(Status::STATUS_ACTIVE);
        $vouchers = CourseVoucher::query()->where('course_id', $member_course->course_id)
                                 ->where('status', Status::STATUS_ACTIVE)->pluck('code', 'id')->toArray();

        $histories = MemberCourseHistory::filter($filter, $member->id, $member_course->course_id)
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
        $data             = $request->all();
        $member_course    = MemberCourse::find($id);
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

            $request->session()->flash('success', "Signed successfully.");
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
            $request->session()->flash('error', "Please check in an appointment.");

            return redirect()->back();
        }
        $member_course->status = MemberCourse::PROGRESSING_STATUS;
        $member_course->save();
        $request->session()->flash('success', "Client using this course.");

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
        $request->session()->flash('success', "Course has been removed from Course progressing list.");

        return redirect()->back();
    }
}
