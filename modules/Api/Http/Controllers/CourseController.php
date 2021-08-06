<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Base\Model\Status;
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
     * @return JsonResponse
     */
    public function list(Request $request){
        $params   = $request->all();
        $item_qty = 10;
        $data     = Course::filter($params)->with('vouchers');
        if (isset($request->price_sort)) {
            $data = $data->orderBy('price', $request->price_sort);
        }
        if (isset($request->item_qty)) {
            $item_qty = $request->item_qty;
        }

        $data = $data->paginate($item_qty);

        return response()->json([
            'status' => 200,
            'data'   => $data
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
            'data'   => $data
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getCourseCate(){
        $data = CourseCategory::query()->where('status', Status::STATUS_ACTIVE)->get();

        return response()->json([
            'status' => 200,
            'data'   => $data
        ]);
    }
}
