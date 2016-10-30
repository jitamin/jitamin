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
use Hiject\Core\Group\GroupManager;
use Hiject\Group\DatabaseBackendGroupProvider;
use Hiject\Group\LdapBackendGroupProvider;

/**
 * Group Provider
 */
class GroupProvider implements ServiceProviderInterface
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
        $container['groupManager'] = new GroupManager;

        if (DB_GROUP_PROVIDER) {
            $container['groupManager']->register(new DatabaseBackendGroupProvider($container));
        }

        if (LDAP_AUTH && LDAP_GROUP_PROVIDER) {
            $container['groupManager']->register(new LdapBackendGroupProvider($container));
        }

        return $container;
    }
}
