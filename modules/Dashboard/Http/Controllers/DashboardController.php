<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Dashboard\Model\Dashboard;


class DashboardController extends Controller{

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
     * @return Application|Factory|View
     */
    public function index(Request $request){

        $count_data = Dashboard::getCountData();

        $appointment_data = Dashboard::getAppointmentData();

        $chart_data['appointment'] = Dashboard::getChartAppointmentData();

        return view('Dashboard::index', compact('count_data', 'appointment_data', 'chart_data'));
    }

    public function errorPage(){
        $error = trans('This action is unauthorized.');
        return view('Dashboard::403', compact('error'));
    }


}
