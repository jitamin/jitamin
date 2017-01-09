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
 * Webhook Notification.
 */
class WebhookNotification extends Base implements NotificationInterface
{
    /**
     * Send notification to a user.
     *
     * @param array  $user
     * @param string $event_name
     * @param array  $event_data
     */
    public function notifyUser(array $user, $event_name, array $event_data)
    {
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
        $url = $this->settingModel->get('webhook_url');
        $token = $this->settingModel->get('webhook_token');

        if (!empty($url)) {
            if (strpos($url, '?') !== false) {
                $url .= '&token='.$token;
            } else {
                $url .= '?token='.$token;
            }

            $payload = [
                'event_name' => $event_name,
                'event_data' => $event_data,
            ];

            $this->httpClient->postJson($url, $payload);
        }
    }
}
