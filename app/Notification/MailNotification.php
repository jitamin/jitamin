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

use Jitamin\Foundation\Base;
use Jitamin\Foundation\Notification\NotificationInterface;

/**
 * Email Notification.
 */
class MailNotification extends Base implements NotificationInterface
{
    /**
     * Notification type.
     *
     * @var string
     */
    const TYPE = 'email';

    /**
     * Send notification to a user.
     *
     * @param array  $user
     * @param string $event_name
     * @param array  $event_data
     */
    public function notifyUser(array $user, $event_name, array $event_data)
    {
        if (!empty($user['email'])) {
            $this->emailClient->send(
                $user['email'],
                $user['name'] ?: $user['username'],
                $this->getMailSubject($event_name, $event_data),
                $this->getMailContent($event_name, $event_data)
            );
        }
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

    /**
     * Get the mail content for a given template name.
     *
     * @param string $event_name Event name
     * @param array  $event_data Event data
     *
     * @return string
     */
    public function getMailContent($event_name, array $event_data)
    {
        return $this->template->render(
            'notification/'.str_replace('.', '_', $event_name),
            $event_data + ['application_url' => $this->settingModel->get('application_url')]
        );
    }

    /**
     * Get the mail subject for a given template name.
     *
     * @param string $eventName Event name
     * @param array  $eventData Event data
     *
     * @return string
     */
    public function getMailSubject($eventName, array $eventData)
    {
        return sprintf(
            '[%s] %s',
            isset($eventData['project_name']) ? $eventData['project_name'] : $eventData['task']['project_name'],
            $this->notificationModel->getTitleWithoutAuthor($eventName, $eventData)
        );
    }
}
