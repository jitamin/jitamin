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

use Jitamin\Foundation\Database\Model;
use PicoDb\Table;

/**
 * Project activity model.
 */
class ProjectActivityModel extends Model
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'project_activities';

    /**
     * Maximum number of events.
     *
     * @var int
     */
    const MAX_EVENTS = 1000;

    /**
     * Add a new event for the project.
     *
     * @param int    $project_id Project id
     * @param int    $task_id    Task id
     * @param int    $creator_id User id
     * @param string $event_name Event name
     * @param array  $data       Event data (will be serialized)
     *
     * @return bool
     */
    public function createEvent($project_id, $task_id, $creator_id, $event_name, array $data)
    {
        $values = [
            'project_id'    => $project_id,
            'task_id'       => $task_id,
            'creator_id'    => $creator_id,
            'event_name'    => $event_name,
            'date_creation' => time(),
            'data'          => json_encode($data),
        ];

        $this->cleanup(self::MAX_EVENTS - 1);

        return $this->db->table(self::TABLE)->insert($values);
    }

    /**
     * Get query.
     *
     * @return Table
     */
    public function getQuery()
    {
        return $this
            ->db
            ->table(self::TABLE)
            ->columns(
                self::TABLE.'.*',
                'uc.username AS author_username',
                'uc.name AS author_name',
                'uc.email',
                'uc.avatar_path'
            )
            ->join(TaskModel::TABLE, 'id', 'task_id')
            ->join(ProjectModel::TABLE, 'id', 'project_id')
            ->left(UserModel::TABLE, 'uc', 'id', self::TABLE, 'creator_id');
    }

    /**
     * Remove old event entries to avoid large table.
     *
     * @param int $max Maximum number of items to keep in the table
     */
    public function cleanup($max)
    {
        $total = $this->db->table(self::TABLE)->count();

        if ($total > $max) {
            $ids = $this->db->table(self::TABLE)->asc('id')->limit($total - $max)->findAllByColumn('id');
            $this->db->table(self::TABLE)->in('id', $ids)->remove();
        }
    }
}
