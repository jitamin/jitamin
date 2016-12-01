<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
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

$config_file = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'config', 'config.php']);

if (file_exists($config_file)) {
    require $config_file;
}

$config_file = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'data', 'config.php']);

if (file_exists($config_file)) {
    require $config_file;
}

require __DIR__.'/constants.php';
require __DIR__.'/env.php';

$container = new Pimple\Container();
$container->register(new Hiject\Providers\MailProvider());
$container->register(new Hiject\Providers\HelperProvider());
$container->register(new Hiject\Providers\SessionProvider());
$container->register(new Hiject\Providers\LoggingProvider());
$container->register(new Hiject\Providers\CacheProvider());
$container->register(new Hiject\Providers\DatabaseProvider());
$container->register(new Hiject\Providers\AuthenticationProvider());
$container->register(new Hiject\Providers\NotificationProvider());
$container->register(new Hiject\Providers\ClassProvider());
$container->register(new Hiject\Providers\EventDispatcherProvider());
$container->register(new Hiject\Providers\GroupProvider());
$container->register(new Hiject\Providers\RouteProvider());
$container->register(new Hiject\Providers\ActionProvider());
$container->register(new Hiject\Providers\ExternalLinkProvider());
$container->register(new Hiject\Providers\AvatarProvider());
$container->register(new Hiject\Providers\FilterProvider());
$container->register(new Hiject\Providers\JobProvider());
$container->register(new Hiject\Providers\QueueProvider());
$container->register(new Hiject\Providers\ApiProvider());
$container->register(new Hiject\Providers\CommandProvider());
$container->register(new Hiject\Providers\PluginProvider());
$container->register(new Hiject\Providers\UpdateProvider());
