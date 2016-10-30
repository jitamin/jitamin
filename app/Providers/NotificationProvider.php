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
use Hiject\Model\UserNotificationTypeModel;
use Hiject\Model\ProjectNotificationTypeModel;
use Hiject\Notification\MailNotification as MailNotification;
use Hiject\Notification\WebNotification as WebNotification;

/**
 * Notification Provider
 */
class NotificationProvider implements ServiceProviderInterface
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
        $container['userNotificationTypeModel'] = function ($container) {
            $type = new UserNotificationTypeModel($container);
            $type->setType(MailNotification::TYPE, t('Email'), '\Hiject\Notification\MailNotification');
            $type->setType(WebNotification::TYPE, t('Web'), '\Hiject\Notification\WebNotification');
            return $type;
        };

        $container['projectNotificationTypeModel'] = function ($container) {
            $type = new ProjectNotificationTypeModel($container);
            $type->setType('webhook', 'Webhook', '\Hiject\Notification\WebhookNotification', true);
            $type->setType('activity_stream', 'ActivityStream', '\Hiject\Notification\ActivityStreamNotification', true);
            return $type;
        };

        return $container;
    }
}
