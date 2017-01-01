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
 * Task Status.
 */
class TaskStatusModel extends Model
{
    /**
     * Return true if the task is closed.
     *
     * @param int $task_id Task id
     *
     * @return bool
     */
    public function isClosed($task_id)
    {
        return $this->checkStatus($task_id, TaskModel::STATUS_CLOSED);
    }

    /**
     * Return true if the task is open.
     *
     * @param int $task_id Task id
     *
     * @return bool
     */
    public function isOpen($task_id)
    {
        return $this->checkStatus($task_id, TaskModel::STATUS_OPEN);
    }

    /**
     * Mark a task closed.
     *
     * @param int $task_id Task id
     *
     * @return bool
     */
    public function close($task_id)
    {
        $this->subtaskStatusModel->closeAll($task_id);

        return $this->changeStatus($task_id, TaskModel::STATUS_CLOSED, time(), TaskModel::EVENT_CLOSE);
    }

    /**
     * Mark a task open.
     *
     * @param int $task_id Task id
     *
     * @return bool
     */
    public function open($task_id)
    {
        return $this->changeStatus($task_id, TaskModel::STATUS_OPEN, 0, TaskModel::EVENT_OPEN);
    }

    /**
     * Close multiple tasks.
     *
     * @param array $task_ids
     */
    public function closeMultipleTasks(array $task_ids)
    {
        foreach ($task_ids as $task_id) {
            $this->close($task_id);
        }
    }

    /**
     * Close all tasks within a column/swimlane.
     *
     * @param int $swimlane_id
     * @param int $column_id
     */
    public function closeTasksBySwimlaneAndColumn($swimlane_id, $column_id)
    {
        $task_ids = $this->db
            ->table(TaskModel::TABLE)
            ->eq('swimlane_id', $swimlane_id)
            ->eq('column_id', $column_id)
            ->eq(TaskModel::TABLE.'.is_active', TaskModel::STATUS_OPEN)
            ->findAllByColumn('id');

        $this->closeMultipleTasks($task_ids);
    }

    /**
     * Common method to change the status of task.
     *
     * @param int    $task_id        Task id
     * @param int    $status         Task status
     * @param int    $date_completed Timestamp
     * @param string $event_name     Event name
     *
     * @return bool
     */
    private function changeStatus($task_id, $status, $date_completed, $event_name)
    {
        if (!$this->taskFinderModel->exists($task_id)) {
            return false;
        }

        $result = $this->db
                        ->table(TaskModel::TABLE)
                        ->eq('id', $task_id)
                        ->update([
                            'is_active'         => $status,
                            'date_completed'    => $date_completed,
                            'date_modification' => time(),
                        ]);

        if ($result) {
            $this->queueManager->push($this->taskEventJob->withParams($task_id, [$event_name]));
        }

        return $result;
    }

    /**
     * Check the status of a task.
     *
     * @param int $task_id Task id
     * @param int $status  Task status
     *
     * @return bool
     */
    private function checkStatus($task_id, $status)
    {
        return $this->db
                    ->table(TaskModel::TABLE)
                    ->eq('id', $task_id)
                    ->eq('is_active', $status)
                    ->count() === 1;
    }
}
