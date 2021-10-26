<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Order\Model\Order;


class OrderController extends Controller{

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
    public function getList(Request $request){
        $data     = Order::filter($request->all());
        $item_qty = 10;
        if (isset($request->item_qty)) {
            $item_qty = $request->item_qty;
        }
        $data = $data->paginate($item_qty);

        return response()->json([
            'status' => 200,
            'data'   => $data
        ]);
    }
}
