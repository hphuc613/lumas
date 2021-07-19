<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Service\Model\Service;


class ServiceController extends Controller{

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request){
        $params = $request->all();
        $data = Service::filter($params)->with('vouchers')->get();

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail($id){
        $data = Service::query()->with('type', 'vouchers')->find($id);

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }
}
