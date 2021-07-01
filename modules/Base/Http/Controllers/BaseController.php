<?php

namespace Modules\Base\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Documentation\Model\Documentation;


class BaseController extends Controller{

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
     * @param $key
     * @return RedirectResponse
     */
    public function changeLocale(Request $request, $key){
        $request->session()->put('locale', $key);
        return redirect()->back();
    }

    /**
     * @return Application|Factory|View
     */
    public function getDocumentation(){
        $documents = Documentation::query()->orderBy('sort')->get();

        return view("Base::documentation.master", compact('documents'));
    }

    /**
     * @param $id
     * @return array|string
     */
    public function getView($id){
        $document = Documentation::query()->find($id);
        return $this->renderAjax("Documentation::_content", compact('document'));
    }
}
