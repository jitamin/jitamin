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

use Jitamin\Core\Database\Model;

/**
 * Task Position.
 */
class TaskPositionModel extends Model
{
    /**
     * Move a task to another column or to another position.
     *
     * @param int  $project_id  Project id
     * @param int  $task_id     Task id
     * @param int  $column_id   Column id
     * @param int  $position    Position (must be >= 1)
     * @param int  $swimlane_id Swimlane id
     * @param bool $fire_events Fire events
     * @param bool $onlyOpen    Do not move closed tasks
     *
     * @return bool
     */
    public function movePosition($project_id, $task_id, $column_id, $position, $swimlane_id = 0, $fire_events = true, $onlyOpen = true)
    {
        if ($position < 1) {
            return false;
        }

        $task = $this->taskFinderModel->getById($task_id);

        if ($onlyOpen && $task['is_active'] == TaskModel::STATUS_CLOSED) {
            return true;
        }

        $result = false;

        if ($task['swimlane_id'] != $swimlane_id) {
            $result = $this->saveSwimlaneChange($project_id, $task_id, $position, $task['column_id'], $column_id, $task['swimlane_id'], $swimlane_id);
        } elseif ($task['column_id'] != $column_id) {
            $result = $this->saveColumnChange($project_id, $task_id, $position, $swimlane_id, $task['column_id'], $column_id);
        } elseif ($task['position'] != $position) {
            $result = $this->savePositionChange($project_id, $task_id, $position, $column_id, $swimlane_id);
        }

        if ($result && $fire_events) {
            $this->fireEvents($task, $column_id, $position, $swimlane_id);
        }

        return $result;
    }

    /**
     * Move a task to another swimlane.
     *
     * @param int $project_id
     * @param int $task_id
     * @param int $position
     * @param int $original_column_id
     * @param int $new_column_id
     * @param int $original_swimlane_id
     * @param int $new_swimlane_id
     *
     * @return bool
     */
    private function saveSwimlaneChange($project_id, $task_id, $position, $original_column_id, $new_column_id, $original_swimlane_id, $new_swimlane_id)
    {
        $this->db->startTransaction();
        $r1 = $this->saveTaskPositions($project_id, $task_id, 0, $original_column_id, $original_swimlane_id);
        $r2 = $this->saveTaskPositions($project_id, $task_id, $position, $new_column_id, $new_swimlane_id);
        $r3 = $this->saveTaskTimestamps($task_id);
        $this->db->closeTransaction();

        return $r1 && $r2 && $r3;
    }

    /**
     * Move a task to another column.
     *
     * @param int $project_id
     * @param int $task_id
     * @param int $position
     * @param int $swimlane_id
     * @param int $original_column_id
     * @param int $new_column_id
     *
     * @return bool
     */
    private function saveColumnChange($project_id, $task_id, $position, $swimlane_id, $original_column_id, $new_column_id)
    {
        $this->db->startTransaction();
        $r1 = $this->saveTaskPositions($project_id, $task_id, 0, $original_column_id, $swimlane_id);
        $r2 = $this->saveTaskPositions($project_id, $task_id, $position, $new_column_id, $swimlane_id);
        $r3 = $this->saveTaskTimestamps($task_id);
        $this->db->closeTransaction();

        return $r1 && $r2 && $r3;
    }

    /**
     * Move a task to another position in the same column.
     *
     * @param int $project_id
     * @param int $task_id
     * @param int $position
     * @param int $column_id
     * @param int $swimlane_id
     *
     * @return bool
     */
    private function savePositionChange($project_id, $task_id, $position, $column_id, $swimlane_id)
    {
        $this->db->startTransaction();
        $result = $this->saveTaskPositions($project_id, $task_id, $position, $column_id, $swimlane_id);
        $this->db->closeTransaction();

        return $result;
    }

    /**
     * Save all task positions for one column.
     *
     * @param int $project_id
     * @param int $task_id
     * @param int $position
     * @param int $column_id
     * @param int $swimlane_id
     *
     * @return bool
     */
    private function saveTaskPositions($project_id, $task_id, $position, $column_id, $swimlane_id)
    {
        $tasks_ids = $this->db->table(TaskModel::TABLE)
            ->eq('is_active', 1)
            ->eq('swimlane_id', $swimlane_id)
            ->eq('project_id', $project_id)
            ->eq('column_id', $column_id)
            ->neq('id', $task_id)
            ->asc('position')
            ->asc('id')
            ->findAllByColumn('id');

        $offset = 1;

        foreach ($tasks_ids as $current_task_id) {

            // Insert the new task
            if ($position == $offset) {
                if (!$this->saveTaskPosition($task_id, $offset, $column_id, $swimlane_id)) {
                    return false;
                }
                $offset++;
            }

            // Rewrite other tasks position
            if (!$this->saveTaskPosition($current_task_id, $offset, $column_id, $swimlane_id)) {
                return false;
            }

            $offset++;
        }

        // Insert the new task at the bottom and normalize bad position
        if ($position >= $offset && !$this->saveTaskPosition($task_id, $offset, $column_id, $swimlane_id)) {
            return false;
        }

        return true;
    }

    /**
     * Update task timestamps.
     *
     * @param int $task_id
     *
     * @return bool
     */
    private function saveTaskTimestamps($task_id)
    {
        $now = time();

        return $this->db->table(TaskModel::TABLE)->eq('id', $task_id)->update([
            'date_moved'        => $now,
            'date_modification' => $now,
        ]);
    }

    /**
     * Save new task position.
     *
     * @param int $task_id
     * @param int $position
     * @param int $column_id
     * @param int $swimlane_id
     *
     * @return bool
     */
    private function saveTaskPosition($task_id, $position, $column_id, $swimlane_id)
    {
        $result = $this->db->table(TaskModel::TABLE)->eq('id', $task_id)->update([
            'position'    => $position,
            'column_id'   => $column_id,
            'swimlane_id' => $swimlane_id,
        ]);

        if (!$result) {
            $this->db->cancelTransaction();

            return false;
        }

        return true;
    }

    /**
     * Fire events.
     *
     * @param array $task
     * @param int   $new_column_id
     * @param int   $new_position
     * @param int   $new_swimlane_id
     */
    private function fireEvents(array $task, $new_column_id, $new_position, $new_swimlane_id)
    {
        $changes = [
            'project_id'         => $task['project_id'],
            'position'           => $new_position,
            'column_id'          => $new_column_id,
            'swimlane_id'        => $new_swimlane_id,
            'src_column_id'      => $task['column_id'],
            'dst_column_id'      => $new_column_id,
            'date_moved'         => $task['date_moved'],
            'recurrence_status'  => $task['recurrence_status'],
            'recurrence_trigger' => $task['recurrence_trigger'],
        ];

        if ($task['swimlane_id'] != $new_swimlane_id) {
            $this->queueManager->push($this->taskEventJob->withParams(
                $task['id'],
                [TaskModel::EVENT_MOVE_SWIMLANE],
                $changes,
                $changes
            ));
        } elseif ($task['column_id'] != $new_column_id) {
            $this->queueManager->push($this->taskEventJob->withParams(
                $task['id'],
                [TaskModel::EVENT_MOVE_COLUMN],
                $changes,
                $changes
            ));
        } elseif ($task['position'] != $new_position) {
            $this->queueManager->push($this->taskEventJob->withParams(
                $task['id'],
                [TaskModel::EVENT_MOVE_POSITION],
                $changes,
                $changes
            ));
        }
    }
}
