<?php

namespace Tir\Crud\Support\Acl;

use Illuminate\Support\Facades\Auth;

class Access
{

    /**
     * Static method for checking access (backward compatibility)
     */
    public static function check(string $module, string $action)
    {
        return (new static())->checkAccess($module, $action);
    }

    /**
     * Static method for executing access check (backward compatibility)
     */
    public static function execute(string $module, string $action): string
    {
        return (new static())->executeAccess($module, $action);
    }

    /**
     * Instance method for checking access
     */
    public function checkAccess(string $module, string $action): bool
    {
        $action = $this->getCrudAction($action);
        $access = Auth::user()->permissions[$module][$action] ?? false;
        return $access;
    }

    /**
     * Instance method for executing access check
     */
    public function executeAccess(string $module, string $action): bool
    {
        $access = $this->checkAccess($module, $action);
        if (!$access) {
            abort(403, 'You have no access to this area');
        }
        return $access;
    }

    private function getCrudAction(string $action): string
    {
        $baseActions = ['data'=>'index', 'select'=>'index', 'store'=>'create', 'update'=>'edit', 'restore'=>'destroy'];
        if(isset($baseActions[$action])){
            return $baseActions[$action];
        }
        return $action;
    }

}
