<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

use Jitamin\Core\Database\Model;

/**
 * User Unread Notification.
 */
class UserUnreadNotificationModel extends Model
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'user_has_unread_notifications';

    /**
     * Add unread notification to someone.
     *
     * @param int    $user_id
     * @param string $event_name
     * @param array  $event_data
     */
    public function create($user_id, $event_name, array $event_data)
    {
        $this->db->table(self::TABLE)->insert([
            'user_id'       => $user_id,
            'date_creation' => time(),
            'event_name'    => $event_name,
            'event_data'    => json_encode($event_data),
        ]);
    }

    /**
     * Get one notification.
     *
     * @param int $notification_id
     *
     * @return array|null
     */
    public function getById($notification_id)
    {
        $notification = $this->db->table(self::TABLE)->eq('id', $notification_id)->findOne();

        if (!empty($notification)) {
            $this->unserialize($notification);
        }

        return $notification;
    }

    /**
     * Get all notifications for a user.
     *
     * @param int $user_id
     *
     * @return array
     */
    public function getAll($user_id)
    {
        $events = $this->db->table(self::TABLE)->eq('user_id', $user_id)->desc('date_creation')->findAll();

        foreach ($events as &$event) {
            $this->unserialize($event);
        }

        return $events;
    }

    /**
     * Mark a notification as read.
     *
     * @param int $user_id
     * @param int $notification_id
     *
     * @return bool
     */
    public function markAsRead($user_id, $notification_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $notification_id)->eq('user_id', $user_id)->remove();
    }

    /**
     * Mark all notifications as read for a user.
     *
     * @param int $user_id
     *
     * @return bool
     */
    public function markAllAsRead($user_id)
    {
        return $this->db->table(self::TABLE)->eq('user_id', $user_id)->remove();
    }

    /**
     * Return true if the user as unread notifications.
     *
     * @param int $user_id
     *
     * @return bool
     */
    public function hasNotifications($user_id)
    {
        return $this->db->table(self::TABLE)->eq('user_id', $user_id)->exists();
    }

    /**
     * Unserialize the event.
     *
     * @param GenericEvent $event
     */
    private function unserialize(&$event)
    {
        $event['event_data'] = json_decode($event['event_data'], true);
        $event['title'] = $this->notificationModel->getTitleWithoutAuthor($event['event_name'], $event['event_data']);
    }
}
