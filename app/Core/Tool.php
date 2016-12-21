<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core;

use Pimple\Container;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Tool class.
 */
class Tool
{
    /**
     * Remove recursively a directory.
     *
     * @static
     *
     * @param string $directory
     * @param bool   $removeDirectory
     */
    public static function removeAllFiles($directory, $removeDirectory = true)
    {
        $it = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        if ($removeDirectory) {
            rmdir($directory);
        }
    }

    /**
     * Build dependency injection container from an array.
     *
     * @static
     *
     * @param Container $container
     * @param array     $namespaces
     *
     * @return Container
     */
    public static function buildDIC(Container $container, array $namespaces)
    {
        foreach ($namespaces as $namespace => $classes) {
            foreach ($classes as $name) {
                $class = '\\Jitamin\\'.$namespace.'\\'.$name;
                $container[lcfirst($name)] = function ($c) use ($class) {
                    return new $class($c);
                };
            }
        }

        return $container;
    }

    /**
     * Build dependency injection container for custom helpers from an array.
     *
     * @static
     *
     * @param Container $container
     * @param array     $namespaces
     *
     * @return Container
     */
    public static function buildDICHelpers(Container $container, array $namespaces)
    {
        foreach ($namespaces as $namespace => $classes) {
            foreach ($classes as $name) {
                $class = '\\Jitamin\\'.$namespace.'\\'.$name;
                $container['helper']->register($name, $class);
            }
        }

        return $container;
    }
}
