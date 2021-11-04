<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;

class Controller extends BaseController{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function renderAjax($view, $compact = NULL){
        return view($view)->with($compact)->render();
    }

    /**
     * @param $items
     * @param int $perPage
     * @param null $page
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 20, $page = null){
        $page  = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath()
        ]);
    }
}
