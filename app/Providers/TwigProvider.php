<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Providers;

use Jitamin\Core\Http\Route;
use Jitamin\Core\Http\Router;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Twig Provider.
 */
class TwigProvider implements ServiceProviderInterface
{
    /**
     * Register providers.
     *
     * @param \Pimple\Container $container
     *
     * @return \Pimple\Container
     */
    public function register(Container $container)
    {
        $loader = new \Twig_Loader_Filesystem(JITAMIN_DIR . '/resources/views');

        $container['twig'] = new \Twig_Environment($loader, [
            'cache' => JITAMIN_DIR . '/storage/cache/',
            'auto_reload' => true,
        ]);

        return $container;
    }
}
