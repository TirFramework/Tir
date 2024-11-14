<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Localization
{
    public function handle(Request $request, Closure $next)
    {

        if((Auth::user()->settings['locale'] ?? null) == 'de'){
            App::setLocale('de');
            return $next($request);
        }
        return $next($request);

    }
}
