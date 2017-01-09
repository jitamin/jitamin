<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Foundation\Plugin;

use Composer\Autoload\ClassLoader;
use DirectoryIterator;
use Jitamin\Foundation\Tool;
use LogicException;

/**
 * Plugin Loader.
 */
class Loader extends \Jitamin\Foundation\Base
{
    /**
     * Plugin instances.
     *
     * @var array
     */
    protected $plugins = [];

    /**
     * Get list of loaded plugins.
     *
     * @return Base[]
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * Scan plugin folder and load plugins.
     */
    public function scan()
    {
        if (file_exists(PLUGINS_DIR)) {
            $loader = new ClassLoader();
            $loader->addPsr4('Jitamin\Plugin\\', PLUGINS_DIR);
            $loader->register();

            $dir = new DirectoryIterator(PLUGINS_DIR);

            foreach ($dir as $fileInfo) {
                if ($fileInfo->isDir() && substr($fileInfo->getFilename(), 0, 1) !== '.') {
                    $pluginName = $fileInfo->getFilename();
                    $this->loadSchema($pluginName);
                    $this->initializePlugin($pluginName, $this->loadPlugin($pluginName));
                }
            }
        }
    }

    /**
     * Load plugin schema.
     *
     * @param string $pluginName
     */
    public function loadSchema($pluginName)
    {
        if (SchemaHandler::hasSchema($pluginName)) {
            $schemaHandler = new SchemaHandler($this->container);
            $schemaHandler->loadSchema($pluginName);
        }
    }

    /**
     * Load plugin.
     *
     * @param string $pluginName
     *
     * @throws LogicException
     *
     * @return Base
     */
    public function loadPlugin($pluginName)
    {
        $className = '\Jitamin\Plugin\\'.$pluginName.'\\Plugin';

        if (!class_exists($className)) {
            throw new LogicException('Unable to load this plugin class '.$className);
        }

        return new $className($this->container);
    }

    /**
     * Initialize plugin.
     *
     * @param string $pluginName
     * @param Base   $plugin
     */
    public function initializePlugin($pluginName, Base $plugin)
    {
        if (method_exists($plugin, 'onStartup')) {
            $this->dispatcher->addListener('app.bootstrap', [$plugin, 'onStartup']);
        }

        Tool::buildDIC($this->container, $plugin->getClasses());
        Tool::buildDICHelpers($this->container, $plugin->getHelpers());

        $plugin->initialize();
        $this->plugins[$pluginName] = $plugin;
    }
}
