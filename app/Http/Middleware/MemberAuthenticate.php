<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class MemberAuthenticate
 * @package App\Http\Middleware
 */
class MemberAuthenticate{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if(empty($request->user('member'))){
            return redirect()->route('frontend.get.login.member');
        }

        return $next($request);
    }
}
