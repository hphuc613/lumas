<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Store\Model\Store;


class StoreController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    public function list(Request $request){
        $data = Store::query();
        if ($request->key) {
            $data = $data->orWhere('name', 'LIKE', '%' . $request->key . '%')
                         ->orWhere('address', 'LIKE', '%' . $request->key . '%')
                         ->orWhere('phone', 'LIKE', '%' . $request->key . '%');
        }

        $data = $data->get();

        return response()->json([
            'status' => 200,
            'data'   => $data
        ]);
    }
}
