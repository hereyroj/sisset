<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "ftp", "s3", "rackspace"
    |
    */

    'default' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => 'your-key',
            'secret' => 'your-secret',
            'region' => 'your-region',
            'bucket' => 'your-bucket',
        ],

        'minio' => [
            'driver' => 's3',
            'endpoint' => env('MINIO_ENDPOINT', 'http://192.168.1.3:9000'),
            'use_path_style_endpoint' => true,
            'key' => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region' => env('AWS_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],

        'imports' => [
            'driver' => 'local',
            'root' => storage_path('app/imports'),
        ],

        'avatars' => [
            'driver' => 'local',
            'root' => storage_path('app/avatars'),
        ],

        'tos' => [
            'driver' => 'local',
            'root' => storage_path('app/tos'),
        ],

        'pqr' => [
            'driver' => 'local',
            'root' => storage_path('app/pqr'),
        ],

        'backup' => [
            'driver' => 'local',
            'root' => base_path('storage/laravel-backups'),
        ],

        'comparendos' => [
            'driver' => 'local',
            'root' => storage_path('app/comparendos'),
        ],

        'turnos' => [
            'driver' => 'local',
            'root' => storage_path('app/turnos'),
        ],

        'parametros' => [
            'driver' => 'local',
            'root' => storage_path('app/public/parametros'),
        ],

        'sanciones' => [
            'driver' => 'local',
            'root' => storage_path('app/sanciones'),
        ],

        'notificacionesAviso' => [
            'driver' => 'local',
            'root' => storage_path('app/notificacionesAviso'),
        ],

        'tramites' => [
            'driver' => 'local',
            'root' => storage_path('app/tramites'),
        ],

        'edictos' => [
            'driver' => 'local',
            'root' => storage_path('app/edictos'),
        ],

        'liquidacionesVehiculos' => [
            'driver' => 'local',
            'root' => storage_path('app/liquidaciones/vehiculos'),
        ],

        'chats' => [
            'driver' => 'local',
            'root' => storage_path('app/chats'),
        ],

        'posts' => [
            'driver' => 'local',
            'root' => storage_path('app/public/posts'),
        ],

        'mandamientos' => [
            'driver' => 'local',
            'root' => storage_path('app/mandamientos'),
        ],

        'acuerdosPagos' => [
            'driver' => 'local',
            'root' => storage_path('app/acuerdosPagos'),
        ],

        'normativas' => [
            'driver' => 'local',
            'root' => storage_path('app/normativas'),
        ],

    ],

];
