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

/**
 * Task Project Move.
 */
class TaskProjectMoveModel extends TaskDuplicationModel
{
    /**
     * Move a task to another project.
     *
     * @param int $task_id
     * @param int $project_id
     * @param int $swimlane_id
     * @param int $column_id
     * @param int $category_id
     * @param int $owner_id
     *
     * @return bool
     */
    public function moveToProject($task_id, $project_id, $swimlane_id = null, $column_id = null, $category_id = null, $owner_id = null)
    {
        $task = $this->taskFinderModel->getById($task_id);
        $values = $this->prepare($project_id, $swimlane_id, $column_id, $category_id, $owner_id, $task);

        $this->checkDestinationProjectValues($values);
        $this->tagDuplicationModel->syncTaskTagsToAnotherProject($task_id, $project_id);

        if ($this->db->table(TaskModel::TABLE)->eq('id', $task_id)->update($values)) {
            $this->queueManager->push($this->taskEventJob->withParams($task_id, [TaskModel::EVENT_MOVE_PROJECT], $values));
        }

        return true;
    }

    /**
     * Prepare new task values.
     *
     * @param int   $project_id
     * @param int   $swimlane_id
     * @param int   $column_id
     * @param int   $category_id
     * @param int   $owner_id
     * @param array $task
     *
     * @return array
     */
    protected function prepare($project_id, $swimlane_id, $column_id, $category_id, $owner_id, array $task)
    {
        $values = [];
        $values['is_active'] = 1;
        $values['project_id'] = $project_id;
        $values['column_id'] = $column_id !== null ? $column_id : $task['column_id'];
        $values['position'] = $this->taskFinderModel->countByColumnId($project_id, $values['column_id']) + 1;
        $values['swimlane_id'] = $swimlane_id !== null ? $swimlane_id : $task['swimlane_id'];
        $values['category_id'] = $category_id !== null ? $category_id : $task['category_id'];
        $values['owner_id'] = $owner_id !== null ? $owner_id : $task['owner_id'];
        $values['priority'] = $task['priority'];

        return $values;
    }
}
