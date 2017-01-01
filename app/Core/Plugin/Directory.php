<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Plugin;

use Jitamin\Core\Base as BaseCore;

/**
 * Class Directory.
 */
class Directory extends BaseCore
{
    /**
     * Get all plugins available.
     *
     * @param string $url
     *
     * @return array
     */
    public function getAvailablePlugins($url = PLUGIN_API_URL)
    {
        $plugins = $this->httpClient->getJson($url);
        $plugins = array_filter($plugins, [$this, 'isCompatible']);
        $plugins = array_filter($plugins, [$this, 'isInstallable']);

        return $plugins;
    }

    /**
     * Filter plugins.
     *
     * @param array  $plugin
     * @param string $appVersion
     *
     * @return bool
     */
    public function isCompatible(array $plugin, $appVersion = APP_VERSION)
    {
        if (strpos($appVersion, 'master') !== false) {
            return true;
        }

        foreach (['>=', '>'] as $operator) {
            if (strpos($plugin['compatible_version'], $operator) === 0) {
                $pluginVersion = substr($plugin['compatible_version'], strlen($operator));

                return version_compare($appVersion, $pluginVersion, $operator);
            }
        }

        return $plugin['compatible_version'] === $appVersion;
    }

    /**
     * Filter plugins.
     *
     * @param array $plugin
     *
     * @return bool
     */
    public function isInstallable(array $plugin)
    {
        return $plugin['remote_install'];
    }
}
