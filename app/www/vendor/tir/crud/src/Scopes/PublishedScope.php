<?php

namespace App\Modules\Crud\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class PublishedScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $user = Auth::User();
        if (!$user || $user->status != 'enabled' || $user->type != 'admin') {
            return $builder->where('status', '!=', 'draft')->where('status', '!=', 'unpublished');
        } else {
            return $builder;
        }
    }
}
