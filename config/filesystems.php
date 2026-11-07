<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

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

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
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
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'botman_attachment' => [
            'driver' => 'local',
            'root' => storage_path('app/public/botman'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

        // Local and staging keep files on this app. Production uses the file server.
        'attachments' => env('APP_ENV') === 'production'
            ? [
                'driver' => env('ATTACHMENT_DRIVER', 'ftp'),
                'host' => env('ATTACHMENT_HOST', '172.16.2.147'),
                'username' => env('ATTACHMENT_USERNAME'),
                'password' => env('ATTACHMENT_PASSWORD'),
                'port' => (int) env('ATTACHMENT_PORT', 21),
                'root' => env('ATTACHMENT_ROOT', '/perolehan-attachment'),
                'passive' => filter_var(env('ATTACHMENT_PASSIVE', true), FILTER_VALIDATE_BOOLEAN),
                'ssl' => filter_var(env('ATTACHMENT_SSL', false), FILTER_VALIDATE_BOOLEAN),
                'timeout' => (int) env('ATTACHMENT_TIMEOUT', 30),
                'throw' => false,
            ]
            : [
                'driver' => 'local',
                'root' => storage_path('app/public'),
                'url' => env('APP_URL').'/storage',
                'visibility' => 'public',
                'throw' => false,
            ],

    ],

];
