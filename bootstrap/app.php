<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Foundation\Application;

$container = new Pimple\Container();

foreach ($configApp['providers'] as $provider) {
    $container->register(new $provider());
}

$app = new Application($container);

return $app;
