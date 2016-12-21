<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

/**
 * User Notification Type.
 */
class UserNotificationTypeModel extends NotificationTypeModel
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'user_has_notification_types';

    /**
     * Get selected notification types for a given user.
     *
     * @param int $user_id
     *
     * @return array
     */
    public function getSelectedTypes($user_id)
    {
        $types = $this->db->table(self::TABLE)->eq('user_id', $user_id)->asc('notification_type')->findAllByColumn('notification_type');

        return $this->filterTypes($types);
    }

    /**
     * Save notification types for a given user.
     *
     * @param int      $user_id
     * @param string[] $types
     *
     * @return bool
     */
    public function saveSelectedTypes($user_id, array $types)
    {
        $results = [];
        $this->db->table(self::TABLE)->eq('user_id', $user_id)->remove();

        foreach ($types as $type) {
            $results[] = $this->db->table(self::TABLE)->insert(['user_id' => $user_id, 'notification_type' => $type]);
        }

        return !in_array(false, $results, true);
    }
}
