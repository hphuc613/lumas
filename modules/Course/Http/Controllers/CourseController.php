<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Base\Model\Status;
use Modules\Course\Http\Requests\CourseRequest;
use Modules\Course\Model\Course;
use Modules\Course\Model\CourseCategory;


class CourseController extends Controller{

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
        $filter            = $request->all();
        $courses           = Course::filter($filter)->orderBy('name')->paginate(50);
        $course_categories = Course::getArray(Status::STATUS_ACTIVE);
        return view("Course::course.index", compact('filter', 'courses', 'course_categories'));
    }

    /**
     * @return Application|Factory|View
     */
    public function getCreate(){
        $statuses          = Status::getStatuses();
        $course_categories = CourseCategory::getArray(Status::STATUS_ACTIVE);
        return view("Course::course.create", compact('statuses', 'course_categories'));
    }

    /**
     * @param CourseRequest $request
     * @return RedirectResponse
     */
    public function postCreate(CourseRequest $request){
        $data   = $request->all();
        $course = new Course($data);
        $course->save();
        $request->session()->flash('success', 'Course created successfully.');

        return redirect()->route('get.course.list');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function getUpdate($id){
        $course            = Course::find($id);
        $statuses          = Status::getStatuses();
        $course_categories = CourseCategory::getArray(Status::STATUS_ACTIVE);
        return view("Course::course.update", compact('statuses', 'course_categories', 'course'));
    }

    /**
     * @param CourseRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function postUpdate(CourseRequest $request, $id){
        $course = Course::find($id);
        $course->update($request->all());
        $request->session()->flash('success', 'Course updated successfully.');

        return redirect()->route('get.course.list');
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function delete(Request $request, $id){
        $course = Course::find($id);
        $course->delete();
        $request->session()->flash('success', 'Course deleted successfully.');

        return redirect()->back();
    }
}
