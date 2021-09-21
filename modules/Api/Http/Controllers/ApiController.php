<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Setting\Model\Website;


class ApiController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    public function getHelperCenter(){
        $setting = Website::getWebsiteConfig();

        return response()->json([
            'status' => 200,
            'data'   => $setting[\Modules\Setting\Model\Website::PHONE_NUMBER] ?? null
        ]);
    }
}
