<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;


class ApiController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    public function test(){
        echo 'Hi Boss, I am Lumas!';
    }
}
