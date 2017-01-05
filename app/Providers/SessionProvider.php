<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Providers;

use Jitamin\Core\Session\FlashMessage;
use Jitamin\Core\Session\SessionManager;
use Jitamin\Core\Session\SessionStorage;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Session Provider.
 */
class SessionProvider implements ServiceProviderInterface
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
        $container['sessionStorage'] = function () {
            return new SessionStorage();
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
