<?php

return [

    'middlewares'=> env('CRUD_MIDDLEWARES',['auth:sanctum']),

    'localization' => env('CRUD_LOCALE',true),

    'accessLevelControl' => env('CRUD_ACCESS_LEVEL_CONTROL','on'),

];
