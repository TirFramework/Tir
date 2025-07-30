<?php

return [

    //This value config which admin panel user in scaffolding system.

    'middlewares'=> env('CRUD_MIDDLEWARES',['auth:sanctum']),

    'localization' => env('CRUD_LOCALE',true),

    'accessLevelControl' => env('CRUD_ACCESS_LEVEL_CONTROL','on'),

    'aclCheckerClass' => \Tir\Crud\Support\Acl\Access::Class,

];
