<?php

namespace Tir\Crud\Support\Eloquent;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Builder;

trait IsTranslatable
{
    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $model->locale = request()->input('locale');
        });

        static::addGlobalScope('locale', function (Builder $builder) {
            $locale = request()->input('locale');
            if(isset($locale)){
                if( $locale !== 'all'){
                    $builder->where('locale', $locale);
                } else {
                    $builder;
                }
            }else{
                $builder->where('locale', App::currentLocale());
            }
        });

    }

}
