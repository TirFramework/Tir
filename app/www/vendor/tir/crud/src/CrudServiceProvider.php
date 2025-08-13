<?php

namespace Tir\Crud;


use Illuminate\Support\ServiceProvider;
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


        $this->app->bind('crud.fields', function () {
            return new \Tir\Crud\Facades\Fields();
        });


        $this->registerNewRouteResource();

        $this->registerModulesSingleton();

        // $this->app->register(CrudSeedServiceProvider::class);

        // $this->app['router']->aliasMiddleware('acl', AclMiddleware::class);
        // $this->app['router']->pushMiddlewareToGroup('*', AclMiddleware::class);
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
