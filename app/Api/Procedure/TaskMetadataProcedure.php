<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Api\Procedure;

use Jitamin\Api\Authorization\TaskAuthorization;

/**
 * Class TaskMetadataProcedure.
 */
class TaskMetadataProcedure extends BaseProcedure
{
    public function getTaskMetadata($task_id)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'getTask', $task_id);

        return $this->taskMetadataModel->getAll($task_id);
    }

    public function getTaskMetadataByName($task_id, $name)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'getTask', $task_id);

        return $this->taskMetadataModel->get($task_id, $name);
    }

    public function saveTaskMetadata($task_id, array $values)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'updateTask', $task_id);

        return $this->taskMetadataModel->save($task_id, $values);
    }

    public function removeTaskMetadata($task_id, $name)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'updateTask', $task_id);

        return $this->taskMetadataModel->remove($task_id, $name);
    }
}
