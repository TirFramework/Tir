<?php

namespace Tir\Crud\Support\Acl;

use Illuminate\Support\Facades\Auth;

class Access
{

    /**
     * Check permissions to access the module
     *
     * @param string $module
     * @param string $action
     * @return string
     */
    public static function check(string $module, string $action)
    {
        $action = static::getCrudAction($action);
        $access = Auth::user()->permissions[$module][$action] ?? 'deny';
        return $access;
    }

    public static function execute(string $module, string $action): string
    {
        $access = Access::check($module, $action);
        if ($access == 'deny') {
            abort(403, 'You have no access to this area');
        }
        return $access;
    }

    private static function getCrudAction(string $action): string
    {
        $baseActions = ['data'=>'index', 'select'=>'index', 'store'=>'create', 'update'=>'edit', 'restore'=>'destroy'];
        if(isset($baseActions[$action])){
            return $baseActions[$action];
        }
        return $action;
    }

}
