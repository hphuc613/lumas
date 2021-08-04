<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Base\Model\Status;
use Modules\Service\Model\Service;
use Modules\Service\Model\ServiceType;


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
     * @return JsonResponse
     */
    public function list(Request $request){
        $params   = $request->all();
        $item_qty = 10;
        if (isset($request->item_qty)) {
            $item_qty = $request->item_qty;
        }
        $data = Service::filter($params)->with('vouchers')->paginate($item_qty);

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
        $data = Service::query()->with('type', 'vouchers')->find($id);

        return response()->json([
            'status' => 200,
            'data'   => $data
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getServiceType(){
        $data = ServiceType::query()->where('status', Status::STATUS_ACTIVE)->get();

        return response()->json([
            'status' => 200,
            'data'   => $data
        ]);
    }
}
