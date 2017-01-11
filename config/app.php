<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */
    'providers' => [
        /*
         * Application Service Providers...
         */
        Jitamin\Providers\MailServiceProvider::class,
        Jitamin\Providers\HelperServiceProvider::class,
        Jitamin\Providers\SessionServiceProvider::class,
        Jitamin\Providers\LoggingServiceProvider::class,
        Jitamin\Providers\CacheServiceProvider::class,
        Jitamin\Providers\DatabaseServiceProvider::class,
        Jitamin\Providers\AuthServiceProvider::class,
        Jitamin\Providers\NotificationServiceProvider::class,
        Jitamin\Providers\ClassServiceProvider::class,
        Jitamin\Providers\EventServiceProvider::class,
        Jitamin\Providers\GroupServiceProvider::class,
        Jitamin\Providers\RouteServiceProvider::class,
        Jitamin\Providers\ActionServiceProvider::class,
        Jitamin\Providers\ExternalLinkServiceProvider::class,
        Jitamin\Providers\AvatarServiceProvider::class,
        Jitamin\Providers\FilterServiceProvider::class,
        Jitamin\Providers\JobServiceProvider::class,
        Jitamin\Providers\QueueServiceProvider::class,
        Jitamin\Providers\ApiServiceProvider::class,
        Jitamin\Providers\ConsoleServiceProvider::class,
        Jitamin\Providers\PluginServiceProvider::class,
        Jitamin\Providers\UpdateServiceProvider::class,
    ],

];
