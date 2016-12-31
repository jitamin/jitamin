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
 * Task Project Duplication.
 */
class TaskProjectDuplicationModel extends TaskDuplicationModel
{
    /**
     * Duplicate a task to another project.
     *
     * @param int $task_id
     * @param int $project_id
     * @param int $swimlane_id
     * @param int $column_id
     * @param int $category_id
     * @param int $owner_id
     *
     * @return bool|int
     */
    public function duplicateToProject($task_id, $project_id, $swimlane_id = null, $column_id = null, $category_id = null, $owner_id = null)
    {
        $values = $this->prepare($task_id, $project_id, $swimlane_id, $column_id, $category_id, $owner_id);
        $this->checkDestinationProjectValues($values);
        $new_task_id = $this->save($task_id, $values);

        if ($new_task_id !== false) {
            $this->tagDuplicationModel->duplicateTaskTagsToAnotherProject($task_id, $new_task_id, $project_id);
        }

        return $new_task_id;
    }

    /**
     * Prepare values before duplication.
     *
     * @param int $task_id
     * @param int $project_id
     * @param int $swimlane_id
     * @param int $column_id
     * @param int $category_id
     * @param int $owner_id
     *
     * @return array
     */
    protected function prepare($task_id, $project_id, $swimlane_id, $column_id, $category_id, $owner_id)
    {
        $values = $this->copyFields($task_id);
        $values['project_id'] = $project_id;
        $values['column_id'] = $column_id !== null ? $column_id : $values['column_id'];
        $values['swimlane_id'] = $swimlane_id !== null ? $swimlane_id : $values['swimlane_id'];
        $values['category_id'] = $category_id !== null ? $category_id : $values['category_id'];
        $values['owner_id'] = $owner_id !== null ? $owner_id : $values['owner_id'];

        return $values;
    }
}
