<?php

namespace Modules\Documentation\Http\Controllers;

use App\AppHelpers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Documentation\Http\Requests\DocumentationRequest;
use Modules\Documentation\Model\Documentation;
use Throwable;


class DocumentationController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    public function index(){
        $documents = Documentation::query()->orderBy('name')->get();

        return view("Documentation::index", compact('documents'));
    }

    /**
     * @param Request $request
     * @return array|RedirectResponse|string
     */
    public function getCreate(Request $request){

        if(!$request->ajax()){
            return redirect()->back();
        }
        return $this->renderAjax("Documentation::_form");
    }

    /**
     * @param DocumentationRequest $request
     * @return array|RedirectResponse|string
     */
    public function postCreate(DocumentationRequest $request){
        $data = $request->all();
        DB::beginTransaction();
        try{
            $data['key'] = Helper::slug($data['name']);
            $document    = new Documentation($data);
            $document->save();

            $request->session()->flash('success', 'Created Successfully');
            DB::commit();
        }catch(Throwable $th){

            $request->session()->flash('danger', 'Create Failed');
            DB::rollBack();
        }

        return redirect()->back();
    }

    /**
     * @param $id
     * @return array|string
     */
    public function getView($id){
        $document = Documentation::query()->find($id);
        return $this->renderAjax("Documentation::_content", compact('document'));
    }

    /**
     * @param DocumentationRequest $request
     * @param $id
     * @return array|string
     */
    public function postUpdate(DocumentationRequest $request, $id){
        $data = $request->all();

        DB::beginTransaction();
        try{
            $document = Documentation::query()->find($id);
            $document->update($data);
            DB::commit();

            return $this->renderAjax("Documentation::_content", compact('document'));
        }catch(Throwable $th){

            DB::rollBack();

            return false;
        }
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @throws \Exception
     */
    public function delete($id){
        $document = Documentation::query()->find($id);
        $document->delete();

        return redirect()->back();
    }
}
