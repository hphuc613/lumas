<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Course\Model\Course;


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
     * @return JsonResponse
     */
    public function list(Request $request){
        $params = $request->all();
        $data = Course::filter($params)->with('vouchers')->get();

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function detail($id){
        $data = Course::query()->with('category', 'vouchers')->find($id);

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }
}
