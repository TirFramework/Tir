<?php

namespace Tir\Crud\Support\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tir\Crud\Support\Acl\Access;

class AclMiddleware
{
    /**
     * Run the request filter.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $moduleName)
    {
        if (config('crud.accessLevelControl') != 'off') {
            if(Auth::id() === null){
               throw new AccessDeniedHttpException('access denied');
            }
            $actionName = explode('@', Route::getCurrentRoute()->getActionName())[1];
            $model = $request->input('crudModel');
            $access = Access::check($moduleName, $actionName);
            if ($access == 'operator') {
                $model::addGlobalScope('accessLevel', function (Builder $builder) {
                    return $builder->where('operator_ids', '=', Auth::id());
                });
            } elseif ($access != 'allow') {
                throw new AccessDeniedHttpException('access denied');
            }
        }

        return $next($request);
    }
}
