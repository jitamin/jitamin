<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Hiject\Core\Session\SessionManager;
use Hiject\Core\Session\SessionStorage;
use Hiject\Core\Session\FlashMessage;

/**
 * Session Provider
 */
class SessionProvider implements ServiceProviderInterface
{
    /**
     * Register providers
     *
     * @access public
     * @param  \Pimple\Container $container
     * @return \Pimple\Container
     */
    public function register(Container $container)
    {
        $container['sessionStorage'] = function () {
            return new SessionStorage;
        };

        $container['sessionManager'] = function ($c) {
            return new SessionManager($c);
        };

        $container['flash'] = function ($c) {
            return new FlashMessage($c);
        };

        return $container;
    }
}
