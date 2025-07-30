<?php

namespace Tir\Crud\Support\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddUserIdToRequestsMiddleware
{
    /**
     * Run the request filter.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->merge(['user_id' => Auth::id()]);
        return $next($request);
    }
}