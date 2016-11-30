<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Bus\Job;

use Hiject\Bus\Event\GenericEvent;

/**
 * Class NotificationJob.
 */
class NotificationJob extends BaseJob
{
    /**
     * Set job parameters.
     *
     * @param GenericEvent $event
     * @param string       $eventName
     *
     * @return $this
     */
    public function withParams(GenericEvent $event, $eventName)
    {
        $this->jobParams = [$event->getAll(), $eventName];

        return $this;
    }

    /**
     * Execute job.
     *
     * @param array  $eventData
     * @param string $eventName
     */
    public function execute(array $eventData, $eventName)
    {
        if (!empty($eventData['mention'])) {
            $this->userNotificationModel->sendUserNotification($eventData['mention'], $eventName, $eventData);
        } else {
            $this->userNotificationModel->sendNotifications($eventName, $eventData);
            $this->projectNotificationModel->sendNotifications($eventData['task']['project_id'], $eventName, $eventData);
        }
    }
}
