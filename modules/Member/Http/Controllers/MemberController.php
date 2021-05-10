<?php

namespace Modules\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class MemberController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    public function index(Request $request){
        return view("Member::backend.index");
    }
}
