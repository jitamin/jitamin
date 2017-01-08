<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Checks for PHP < 5.6
if (version_compare(PHP_VERSION, '5.6.0', '<')) {
    throw new Exception('This software require PHP 5.6.0 minimum');
}

// Check data folder if sqlite
if (DB_DRIVER === 'sqlite' && !is_writable(DATA_DIR.DIRECTORY_SEPARATOR.DB_FILENAME.'.sqlite')) {
    throw new Exception('The directory "'.DB_FILENAME.'" must be writeable by your web server user');
}

// Check PDO extensions
if (DB_DRIVER === 'sqlite' && !extension_loaded('pdo_sqlite')) {
    throw new Exception('PHP extension required: "pdo_sqlite"');
}

if (DB_DRIVER === 'mysql' && !extension_loaded('pdo_mysql')) {
    throw new Exception('PHP extension required: "pdo_mysql"');
}

if (DB_DRIVER === 'postgres' && !extension_loaded('pdo_pgsql')) {
    throw new Exception('PHP extension required: "pdo_pgsql"');
}

// Check other extensions
foreach (['gd', 'mbstring', 'hash', 'openssl', 'json', 'hash', 'ctype', 'filter', 'session'] as $ext) {
    if (!extension_loaded($ext)) {
        throw new Exception('PHP extension required: "'.$ext.'"');
    }
}

// Fix wrong value for arg_separator.output, used by the function http_build_query()
if (ini_get('arg_separator.output') === '&amp;') {
    ini_set('arg_separator.output', '&');
}
