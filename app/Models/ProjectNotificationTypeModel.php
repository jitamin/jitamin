<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Model;

/**
 * Project Notification Type.
 */
class ProjectNotificationTypeModel extends NotificationTypeModel
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'project_has_notification_types';

    /**
     * Get selected notification types for a given project.
     *
     * @param int $project_id
     *
     * @return array
     */
    public function getSelectedTypes($project_id)
    {
        $types = $this->db
            ->table(self::TABLE)
            ->eq('project_id', $project_id)
            ->asc('notification_type')
            ->findAllByColumn('notification_type');

        return $this->filterTypes($types);
    }

    /**
     * Save notification types for a given project.
     *
     * @param int      $project_id
     * @param string[] $types
     *
     * @return bool
     */
    public function saveSelectedTypes($project_id, array $types)
    {
        $results = [];
        $this->db->table(self::TABLE)->eq('project_id', $project_id)->remove();

        foreach ($types as $type) {
            $results[] = $this->db->table(self::TABLE)->insert(['project_id' => $project_id, 'notification_type' => $type]);
        }

        return !in_array(false, $results, true);
    }
}
