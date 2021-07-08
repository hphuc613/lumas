<?php

namespace App\Http\Middleware;

use App\AppHelpers\Helper;
use Closure;
use Illuminate\Http\Request;

/**
 * Class MemberAuthenticate
 * @package App\Http\Middleware
 */
class ApiUserAuthenticate{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        Helper::apiResponseByLanguage($request);

        if(empty($request->user('api-user'))){
            return response()->json(['status' => 401, 'error' => 'Unauthorized']);
        }

        return $next($request);
    }
}
