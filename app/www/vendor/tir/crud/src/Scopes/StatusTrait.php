<?php

namespace App\Modules\Crud\Scopes;

trait StatusTrait
{


    public static function scopeHideUnPublished($query)
    {
        return $query->where('status', '!=', 'unpublished');
    }

    public static function scopeHideDraft($query)
    {
        return $query->where('status', '!=', 'draft');
    }


}
