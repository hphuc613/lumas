<?php

namespace Modules\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


class FrontendController extends Controller{


    public function __construct(){
        # parent::__construct();
    }
    public function memberInfo(Request $request){
        return view("Member::frontend.index");
    }
}
