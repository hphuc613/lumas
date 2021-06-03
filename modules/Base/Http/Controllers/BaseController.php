<?php

namespace Modules\Base\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class BaseController extends Controller
{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        # parent::__construct();
    }

    /**
     * @param Request $request
     * @param $key
     * @return RedirectResponse
     */
    public function changeLocale(Request $request, $key)
    {
        $request->session()->put('locale', $key);
        return redirect()->back();
    }
}
