<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Notification;

use Jitamin\Core\Base;
use Jitamin\Core\Notification\NotificationInterface;

/**
 * Web Notification.
 */
class WebNotification extends Base implements NotificationInterface
{
    /**
     * Notification type.
     *
     * @var string
     */
    const TYPE = 'web';

    /**
     * Send notification to a user.
     *
     * @param array  $user
     * @param string $event_name
     * @param array  $event_data
     */
    public function notifyUser(array $user, $event_name, array $event_data)
    {
        $this->userUnreadNotificationModel->create($user['id'], $event_name, $event_data);
    }

    /**
     * Send notification to a project.
     *
     * @param array  $project
     * @param string $event_name
     * @param array  $event_data
     */
    public function notifyProject(array $project, $event_name, array $event_data)
    {
    }
}
