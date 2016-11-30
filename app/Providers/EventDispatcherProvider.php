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

use Hiject\Bus\Subscriber\AuthSubscriber;
use Hiject\Bus\Subscriber\BootstrapSubscriber;
use Hiject\Bus\Subscriber\LdapUserPhotoSubscriber;
use Hiject\Bus\Subscriber\NotificationSubscriber;
use Hiject\Bus\Subscriber\ProjectDailySummarySubscriber;
use Hiject\Bus\Subscriber\ProjectModificationDateSubscriber;
use Hiject\Bus\Subscriber\RecurringTaskSubscriber;
use Hiject\Bus\Subscriber\TransitionSubscriber;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class EventDispatcherProvider.
 */
class EventDispatcherProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['dispatcher'] = new EventDispatcher();
        $container['dispatcher']->addSubscriber(new BootstrapSubscriber($container));
        $container['dispatcher']->addSubscriber(new AuthSubscriber($container));
        $container['dispatcher']->addSubscriber(new ProjectDailySummarySubscriber($container));
        $container['dispatcher']->addSubscriber(new ProjectModificationDateSubscriber($container));
        $container['dispatcher']->addSubscriber(new NotificationSubscriber($container));
        $container['dispatcher']->addSubscriber(new TransitionSubscriber($container));
        $container['dispatcher']->addSubscriber(new RecurringTaskSubscriber($container));

        if (LDAP_AUTH && LDAP_USER_ATTRIBUTE_PHOTO !== '') {
            $container['dispatcher']->addSubscriber(new LdapUserPhotoSubscriber($container));
        }

        return $container;
    }
}
