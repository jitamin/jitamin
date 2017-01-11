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

use Jitamin\Model\ProjectNotificationTypeModel;
use Jitamin\Model\UserNotificationTypeModel;
use Jitamin\Notification\MailNotification as MailNotification;
use Jitamin\Notification\WebNotification as WebNotification;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class of Notification Service Provider.
 */
class NotificationServiceProvider implements ServiceProviderInterface
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
        $container['userNotificationTypeModel'] = function ($container) {
            $type = new UserNotificationTypeModel($container);
            $type->setType(MailNotification::TYPE, t('Email'), '\Jitamin\Notification\MailNotification');
            $type->setType(WebNotification::TYPE, t('Web'), '\Jitamin\Notification\WebNotification');

            return $type;
        };

        $container['projectNotificationTypeModel'] = function ($container) {
            $type = new ProjectNotificationTypeModel($container);
            $type->setType('webhook', 'Webhook', '\Jitamin\Notification\WebhookNotification', true);
            $type->setType('activity', 'Activity', '\Jitamin\Notification\ActivityNotification', true);

            return $type;
        };

        return $container;
    }
}
