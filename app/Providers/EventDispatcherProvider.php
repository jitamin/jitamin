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

use Jitamin\Bus\Subscriber\AuthSubscriber;
use Jitamin\Bus\Subscriber\BootstrapSubscriber;
use Jitamin\Bus\Subscriber\LdapUserPhotoSubscriber;
use Jitamin\Bus\Subscriber\NotificationSubscriber;
use Jitamin\Bus\Subscriber\ProjectDailySummarySubscriber;
use Jitamin\Bus\Subscriber\ProjectModificationDateSubscriber;
use Jitamin\Bus\Subscriber\RecurringTaskSubscriber;
use Jitamin\Bus\Subscriber\TransitionSubscriber;
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
