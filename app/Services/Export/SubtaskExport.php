<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Export;

use Jitamin\Core\Base;
use Jitamin\Model\SubtaskModel;
use Jitamin\Model\TaskModel;
use Jitamin\Model\UserModel;

/**
 * Subtask Export.
 */
class SubtaskExport extends Base
{
    /**
     * Subtask statuses.
     *
     * @var array
     */
    private $subtask_status = [];

    /**
     * Fetch subtasks and return the prepared CSV.
     *
     * @param int   $project_id Project id
     * @param mixed $from       Start date (timestamp or user formatted date)
     * @param mixed $to         End date (timestamp or user formatted date)
     *
     * @return array
     */
    public function export($project_id, $from, $to)
    {
        $this->subtask_status = $this->subtaskModel->getStatusList();
        $subtasks = $this->getSubtasks($project_id, $from, $to);
        $results = [$this->getColumns()];

        foreach ($subtasks as $subtask) {
            $results[] = $this->format($subtask);
        }

        return $results;
    }

    /**
     * Get column titles.
     *
     * @return string[]
     */
    public function getColumns()
    {
        return [
            e('Subtask Id'),
            e('Title'),
            e('Status'),
            e('Assignee'),
            e('Time estimated'),
            e('Time spent'),
            e('Task Id'),
            e('Task Title'),
        ];
    }

    /**
     * Format the output of a subtask array.
     *
     * @param array $subtask Subtask properties
     *
     * @return array
     */
    public function format(array $subtask)
    {
        $values = [];
        $values[] = $subtask['id'];
        $values[] = $subtask['title'];
        $values[] = $this->subtask_status[$subtask['status']];
        $values[] = $subtask['assignee_name'] ?: $subtask['assignee_username'];
        $values[] = $subtask['time_estimated'];
        $values[] = $subtask['time_spent'];
        $values[] = $subtask['task_id'];
        $values[] = $subtask['task_title'];

        return $values;
    }

    /**
     * Get all subtasks for a given project.
     *
     * @param int   $project_id Project id
     * @param mixed $from       Start date (timestamp or user formatted date)
     * @param mixed $to         End date (timestamp or user formatted date)
     *
     * @return array
     */
    public function getSubtasks($project_id, $from, $to)
    {
        if (!is_numeric($from)) {
            $from = $this->dateParser->removeTimeFromTimestamp($this->dateParser->getTimestamp($from));
        }

        if (!is_numeric($to)) {
            $to = $this->dateParser->removeTimeFromTimestamp(strtotime('+1 day', $this->dateParser->getTimestamp($to)));
        }

        return $this->db->table(SubtaskModel::TABLE)
                        ->eq('project_id', $project_id)
                        ->columns(
                            SubtaskModel::TABLE.'.*',
                            UserModel::TABLE.'.username AS assignee_username',
                            UserModel::TABLE.'.name AS assignee_name',
                            TaskModel::TABLE.'.title AS task_title'
                        )
                        ->gte('date_creation', $from)
                        ->lte('date_creation', $to)
                        ->join(TaskModel::TABLE, 'id', 'task_id')
                        ->join(UserModel::TABLE, 'id', 'user_id')
                        ->asc(SubtaskModel::TABLE.'.id')
                        ->findAll();
    }
}
