<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Core\Controller\Runner;

try {
    require __DIR__ . '/../bootstrap/autoload.php';
    $container['router']->dispatch();
    $runner = new Runner($container);
    $runner->execute();
} catch (Exception $e) {
    echo 'Internal Error: ' . $e->getMessage();
}
