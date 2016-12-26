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
        $loader = new \Twig_Loader_Filesystem(JITAMIN_DIR.'/resources/views');

        $container['twig'] = new \Twig_Environment($loader, [
            'cache'       => JITAMIN_DIR.'/storage/cache/',
            'auto_reload' => true,
        ]);

        $container['twig']->addGlobal('app', $container['helper']->app);
        $container['twig']->addGlobal('url', $container['helper']->url);
        $container['twig']->addGlobal('asset', $container['helper']->asset);
        $container['twig']->addGlobal('APP_VERSION', APP_VERSION);

        return $container;
    }
}
