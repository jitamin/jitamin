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

use Jitamin\Foundation\Group\GroupManager;
use Jitamin\Group\DatabaseBackendGroupProvider;
use Jitamin\Group\LdapBackendGroupProvider;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class of Group Service Provider.
 */
class GroupServiceProvider implements ServiceProviderInterface
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
        $container['groupManager'] = new GroupManager();

        if (DB_GROUP_PROVIDER) {
            $container['groupManager']->register(new DatabaseBackendGroupProvider($container));
        }

        if (LDAP_AUTH && LDAP_GROUP_PROVIDER) {
            $container['groupManager']->register(new LdapBackendGroupProvider($container));
        }

        return $container;
    }
}
