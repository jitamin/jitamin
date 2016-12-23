<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'/../vendor/autoload.php';

$dbUrlParser = new PicoDb\UrlParser();

if ($dbUrlParser->isEnvironmentVariableDefined()) {
    $dbSettings = $dbUrlParser->getSettings();

    define('DB_DRIVER', $dbSettings['driver']);
    define('DB_USERNAME', $dbSettings['username']);
    define('DB_PASSWORD', $dbSettings['password']);
    define('DB_HOSTNAME', $dbSettings['hostname']);
    define('DB_PORT', $dbSettings['port']);
    define('DB_NAME', $dbSettings['database']);
}

require __DIR__.DIRECTORY_SEPARATOR.'../config/app.php';
$db = require __DIR__.DIRECTORY_SEPARATOR.'../config/database.php';

require __DIR__.'/bootstrap.php';
require __DIR__.'/constants.php';
require __DIR__.'/env.php';

$container = new Pimple\Container();
$container->register(new Jitamin\Providers\MailProvider());
$container->register(new Jitamin\Providers\HelperProvider());
$container->register(new Jitamin\Providers\SessionProvider());
$container->register(new Jitamin\Providers\LoggingProvider());
$container->register(new Jitamin\Providers\CacheProvider());
$container->register(new Jitamin\Providers\DatabaseProvider());
$container->register(new Jitamin\Providers\AuthenticationProvider());
$container->register(new Jitamin\Providers\NotificationProvider());
$container->register(new Jitamin\Providers\ClassProvider());
$container->register(new Jitamin\Providers\EventDispatcherProvider());
$container->register(new Jitamin\Providers\GroupProvider());
$container->register(new Jitamin\Providers\RouteProvider());
$container->register(new Jitamin\Providers\ActionProvider());
$container->register(new Jitamin\Providers\ExternalLinkProvider());
$container->register(new Jitamin\Providers\AvatarProvider());
$container->register(new Jitamin\Providers\FilterProvider());
$container->register(new Jitamin\Providers\JobProvider());
$container->register(new Jitamin\Providers\QueueProvider());
$container->register(new Jitamin\Providers\ApiProvider());
$container->register(new Jitamin\Providers\CommandProvider());
$container->register(new Jitamin\Providers\PluginProvider());
$container->register(new Jitamin\Providers\UpdateProvider());
