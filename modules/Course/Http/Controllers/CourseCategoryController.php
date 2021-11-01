<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Base\Model\Status;
use Modules\Course\Http\Requests\CourseCategoryRequest;
use Modules\Course\Model\CourseCategory;


class CourseCategoryController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    public function index(Request $request){
        $filter            = $request->all();
        $course_categories = CourseCategory::filter($filter)->orderBy('name')->paginate(50);
        return view("Course::course_category.index", compact('filter', 'course_categories'));
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function getCreate(Request $request){
        $statuses = Status::getStatuses();

        if(!$request->ajax()){
            return redirect()->back();
        }

        return $this->renderAjax('Course::course_category.form', compact('statuses'));
    }

    /**
     * @param CourseCategoryRequest $request
     * @return RedirectResponse
     */
    public function postCreate(CourseCategoryRequest $request){
        $category = new CourseCategory($request->all());
        $category->save();
        $request->session()->flash('success', trans('Course Category created successfully.'));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|RedirectResponse|string
     */
    public function getUpdate(Request $request, $id){
        $course_category = CourseCategory::find($id);
        $statuses        = Status::getStatuses();
        if(!$request->ajax()){
            return redirect()->back();
        }
        return $this->renderAjax('Course::course_category.form', compact('course_category', 'statuses'));
    }

    /**
     * @param CourseCategoryRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function postUpdate(CourseCategoryRequest $request, $id){
        $course_category = CourseCategory::find($id);
        $course_category->update($request->all());
        $request->session()->flash('success', trans('Course Category updated successfully.'));

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function delete(Request $request, $id){
        $course_category = CourseCategory::find($id);
        if($course_category->courses->isEmpty()){
            $course_category->delete();
            $request->session()->flash('success', trans('Course Category deleted successfully.'));
        }else{
            $request->session()->flash('error',
                trans('This Course Category cannot delete because has active courses belongs this type'));
        }

        return redirect()->back();
    }
}
