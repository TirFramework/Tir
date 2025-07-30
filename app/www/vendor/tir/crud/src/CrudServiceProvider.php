<?php

namespace Tir\Crud;


use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Tir\Crud\Providers\CrudSeedServiceProvider;
use Tir\Crud\Support\Middleware\AclMiddleware;
use Tir\Crud\Support\Middleware\AddUserIdToRequestsMiddleware;
use Tir\Crud\Support\Middleware\SetLocaleMiddleware;
use Tir\Crud\Support\Module\AdminMenu;
use Tir\Crud\Support\Module\Modules;
use Tir\Crud\Support\Resource\ResourceRegistrar;

class CrudServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/crud.php', 'crud'
        );
        $this->loadTranslationsFrom(__DIR__ . '/Resources/Lang/', 'core');


        $this->registerNewRouteResource();

        $this->registerModulesSingleton();

        $this->app->register(CrudSeedServiceProvider::class);

        $this->app['router']->aliasMiddleware('acl', AclMiddleware::class);
        $this->app['router']->pushMiddlewareToGroup('*', AclMiddleware::class);
        $this->adminMenu();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/crud.php' => config_path('crud.php'),
        ]);


        $this->app['router']->aliasMiddleware('setLocale', SetLocaleMiddleware::class);
        $this->app['router']->pushMiddlewareToGroup('*', SetLocaleMiddleware::class);




    }


    /**
     * Register several route resource those use in CRUD Module
     *
     * @return void
     */
    private function registerNewRouteResource()
    {
        $registrar = new ResourceRegistrar($this->app['router']);
        $this->app->bind('Illuminate\Routing\ResourceRegistrar', function () use ($registrar) {
            return $registrar;
        });
    }


    /**
     * Register a singleton container
     */

    private function registerModulesSingleton()
    {
        Modules::init();
    }

    private function adminMenu()
    {
        $this->app->singleton('AdminMenu', function ($app) {
            return new AdminMenu;
        });
    }
}
