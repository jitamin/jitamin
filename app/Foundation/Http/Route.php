<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Foundation\Http;

use Jitamin\Foundation\Base;

/**
 * Route Handler.
 */
class Route extends Base
{
    /**
     * Flag that enable the routing table.
     *
     * @var bool
     */
    private $activated = false;

    /**
     * Store routes for path lookup.
     *
     * @var array
     */
    private $paths = [];

    /**
     * Store routes for url lookup.
     *
     * @var array
     */
    private $urls = [];

    /**
     * Enable routing table.
     *
     * @return Route
     */
    public function enable()
    {
        $this->activated = true;

        return $this;
    }

    /**
     * Add route.
     *
     * @param string $path
     * @param string $controller
     * @param string $action
     * @param string $plugin
     *
     * @return Route
     */
    public function addRoute($path, $controller, $action, $plugin = '')
    {
        if ($this->activated) {
            $path = ltrim($path, '/');
            $items = explode('/', $path);
            $params = $this->findParams($path);

            $this->paths[] = [
                'items'      => $items,
                'count'      => count($items),
                'controller' => $controller,
                'action'     => $action,
                'plugin'     => $plugin,
            ];

            $this->urls[$plugin][$controller][$action][] = [
                'path'   => $path,
                'params' => $params,
                'count'  => count($params),
            ];
        }

        return $this;
    }

    /**
     * Find a route according to the given path.
     *
     * @param string $path
     *
     * @return array
     */
    public function findRoute($path)
    {
        $items = explode('/', ltrim($path, '/'));
        $count = count($items);
        foreach ($this->paths as $route) {
            if ($count === $route['count']) {
                $params = [];

                for ($i = 0; $i < $count; $i++) {
                    if ($route['items'][$i][0] === '{') {
                        $params[str_replace(['{', '}'], '', $route['items'][$i])] = $items[$i];
                    } elseif ($route['items'][$i] !== $items[$i]) {
                        break;
                    }
                }
                if ($i === $count) {
                    $this->request->setParams($params);

                    return [
                        'controller' => $route['controller'],
                        'action'     => $route['action'],
                        'plugin'     => $route['plugin'],
                    ];
                }
            }
        }

        return [
                'controller' => Router::DEFAULT_CONTROLLER,
                'action'     => Router::DEFAULT_METHOD,
                'plugin'     => '',
            ];
    }

    /**
     * Find route url.
     *
     * @param string $controller
     * @param string $action
     * @param array  $params
     * @param string $plugin
     *
     * @return string
     */
    public function findUrl($controller, $action, array $params = [], $plugin = '')
    {
        if ($plugin === '' && isset($params['plugin'])) {
            $plugin = $params['plugin'];
            unset($params['plugin']);
        }

        if (!isset($this->urls[$plugin][$controller][$action])) {
            return '';
        }

        foreach ($this->urls[$plugin][$controller][$action] as $route) {
            if (array_diff_key($params, $route['params']) === []) {
                $url = $route['path'];
                $i = 0;

                foreach ($params as $variable => $value) {
                    $url = str_replace('{'.$variable.'}', $value, $url);
                    $i++;
                }

                if ($i === $route['count']) {
                    return $url;
                }
            }
        }

        return '';
    }

    /**
     * Load route data from cache.
     *
     * @param array $routes
     */
    public function loadCacheData($routes)
    {
        $this->urls = $routes['urls'];
        $this->paths = $routes['paths'];
    }

    /**
     * Get route data.
     *
     * @return array
     */
    public function getRouteData()
    {
        return [
            'urls'  => $this->urls,
            'paths' => $this->paths,
        ];
    }

    /**
     * Find url params.
     *
     * @param array $items
     *
     * @return array
     */
    public function findParams($path)
    {
        preg_match_all('/\{(.*?)\}/', $path, $matches);

        return array_fill_keys($matches[1], true);
    }
}
