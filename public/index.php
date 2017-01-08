<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Register The Auto Loader
require __DIR__.'/../bootstrap/autoload.php';

// This bootstraps the framework and gets it ready for use
$app = require_once __DIR__.'/../bootstrap/app.php';

// Run The Application
try {
    $app->execute();
} catch (Exception $e) {
    echo 'Internal Error: '.$e->getMessage();
}
