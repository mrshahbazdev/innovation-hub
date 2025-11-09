<?php

use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Middleware\AuthenticateSession;

return [

    /*
    |--------------------------------------------------------------------------
    | Jetstream Stack
    |--------------------------------------------------------------------------
    |
    | This configuration value informs Jetstream which "stack" you will be
    | using for your application. In general, this value is set for you
    | during installation and will not need to be changed after that.
    |
    */

    'stack' => 'livewire',

    /*
    |--------------------------------------------------------------------------
    | Jetstream Route Middleware
    |--------------------------------------------------------------------------
    |
    | Here you may specify which middleware Jetstream will assign to the routes
    | that it registers with the application. When necessary, you may modify
    | these middleware; however, this default value is usually sufficient.
    |
    */

    'middleware' => ['web'],

    'auth_session' => AuthenticateSession::class,

    /*
    |--------------------------------------------------------------------------
    | Jetstream Guard
    |--------------------------------------------------------------------------
    |
    | Here you may specify the authentication guard Jetstream will use while
    | authenticating users. This value should correspond with one of your
    | guards that is already present in your "auth" configuration file.
    |
    */

    'guard' => 'sanctum',

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Some of Jetstream's features are optional. You may disable the features
    | by removing them from this array. You're free to only remove some of
    | these features or you can even remove all of these if you need to.
    |
    */
    /*
    |--------------------------------------------------------------------------
    | Jetstream Roles
    |--------------------------------------------------------------------------
    |
    | Jetstream will automatically define the roles listed below. However,
    | you are free to add or remove roles as needed for your application.
    |
    */

    'roles' => [
        [
            'name' => 'Admin',
            'key' => 'admin',
            'description' => 'Administrator users can perform any action.',
            'permissions' => ['*'],
        ],
        [
            'name' => 'Editor',
            'key' => 'editor',
            'description' => 'Editor users have the ability to read, create, and update.',
            'permissions' => [
                'read',
                'create',
                'update',
            ],
        ],

        // --- Hamare Naye Roles ---
        [
            'name' => 'Work-Bees',
            'key' => 'work-bees',
            'description' => 'Work-Bees users can update prioritization (Yellow Columns).',
            'permissions' => [
                'read',
                'update-yellow',
            ],
        ],
        [
            'name' => 'Developer',
            'key' => 'developer',
            'description' => 'Developer users can update pricing and solutions (Red Columns).',
            'permissions' => [
                'read',
                'update-red',
            ],
        ],
    ],

    /*  --------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    | Here you may enable or disable the various features provided by Jetstream.
    | Some features are disabled by default.
    |*/

    'features' => [
        // Features::termsAndPrivacyPolicy(),
        // Features::profilePhotos(),
        // Features::api(),
        Features::teams(['invitations' => true]),
        Features::accountDeletion(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Profile Photo Disk
    |--------------------------------------------------------------------------
    |
    | This configuration value determines the default disk that will be used
    | when storing profile photos for your application's users. Typically
    | this will be the "public" disk but you may adjust this if needed.
    |
    */

    'profile_photo_disk' => 'public',

];
