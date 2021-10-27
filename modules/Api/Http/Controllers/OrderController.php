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
    public function list(Request $request){
        $query    = Order::query()->with('creator', 'paymentMethod');
        $item_qty = 10;
        if (isset($request->member_id)) {
            $query = $query->where('member_id', $request->member_id);
        }
        if (isset($request->item_qty)) {
            $item_qty = $request->item_qty;
        }
        $query = $query->orderBy('updated_at', 'DESC')->paginate($item_qty);

        $data = [];
        foreach($query as $key => $item) {
            $data[$key]["id"]             = $item->id;
            $data[$key]["code"]           = is_numeric($item->code) ? 'CWB'.$item->code : $item->code;
            $data[$key]["member_id"]      = $item->member_id;
            $data[$key]["remarks"]        = $item->remarks;
            $data[$key]["total_price"]    = $item->total_price;
            $data[$key]["status"]         = $item->status;
            $data[$key]["order_type"]     = $item->order_type;
            $data[$key]["creator"]        = $item->creator->name ?? NULL;
            $data[$key]["created_at"]     = $item->created_at;
            $data[$key]["updated_at"]     = $item->updated_at;
            $data[$key]["payment_method"] = $item->paymentMethod->name ?? NULL;
        }

        return response()->json([
            'status' => 200,
            'data'   => $data
        ]);
    }

    public function detail(Request $request, $id){
        $data          = Order::query()->with('creator', 'paymentMethod', 'orderDetails')->where('id', $id)->first();
        $orderDetails = $data->orderDetails;
        $details = [];
        foreach($orderDetails as $key => $item){
            $details[$key]['id'] = $item->id;
            $details[$key]['product'] = $item->product->name ?? NULL;
            $details[$key]['product_price'] = $item->product_price;
            $details[$key]['voucher'] = $item->productVoucher->code ?? NULL;
            $details[$key]['voucher_price'] = $item->voucher_price ?? NULL;
            $details[$key]['price'] = $item->price;
            $details[$key]['quantity'] = $item->quantity;
            $details[$key]['amount'] = $item->amount;
            $details[$key]['created_at'] = formatDate(strtotime($item->created_at), 'Y-m-d H:i:s');
            $details[$key]['updated_at'] = formatDate(strtotime($item->updated_at), 'Y-m-d H:i:s');
        }

        $creator = $data->creator->name ?? NULL;
        $payment_method = $data->paymentMethod->name ?? NULL;
        $data = $data->toArray();
        $data['code'] = is_numeric($data['code']) ? 'CWB'.$data['code'] : $data['code'];
        $data['payment_method'] = $payment_method;
        $data['creator'] = $creator;
        $data['order_details'] = $details;

        return response()->json([
            'status' => 200,
            'data'   => $data
        ]);
    }
}
