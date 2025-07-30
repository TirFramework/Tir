<?php

namespace Tir\Crud\Support\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetLocaleMiddleware
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
        $locale = $request->route('locale');
        
        if(isset($locale)){
            App::setLocale($locale);
        }

        return $next($request);
    }
}