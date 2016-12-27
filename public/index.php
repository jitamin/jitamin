<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Core\Controller\Runner;

try {
    require __DIR__.'/../bootstrap/autoload.php';
    $container['router']->dispatch();

    if ($container['router']->getController() === 'Api') {
        echo $container['api']->execute();
    } else {
        $runner = new Runner($container);
        $runner->execute();
    }
} catch (Exception $e) {
    echo 'Internal Error: '.$e->getMessage();
}
