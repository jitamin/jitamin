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
        Jitamin\Providers\MailProvider::class,
        Jitamin\Providers\HelperProvider::class,
        Jitamin\Providers\SessionProvider::class,
        Jitamin\Providers\LoggingProvider::class,
        Jitamin\Providers\CacheProvider::class,
        Jitamin\Providers\DatabaseProvider::class,
        Jitamin\Providers\AuthenticationProvider::class,
        Jitamin\Providers\NotificationProvider::class,
        Jitamin\Providers\ClassProvider::class,
        Jitamin\Providers\EventDispatcherProvider::class,
        Jitamin\Providers\GroupProvider::class,
        Jitamin\Providers\RouteProvider::class,
        Jitamin\Providers\ActionProvider::class,
        Jitamin\Providers\ExternalLinkProvider::class,
        Jitamin\Providers\AvatarProvider::class,
        Jitamin\Providers\FilterProvider::class,
        Jitamin\Providers\JobProvider::class,
        Jitamin\Providers\QueueProvider::class,
        Jitamin\Providers\ApiProvider::class,
        Jitamin\Providers\CommandProvider::class,
        Jitamin\Providers\PluginProvider::class,
        Jitamin\Providers\UpdateProvider::class,
    ],

];
