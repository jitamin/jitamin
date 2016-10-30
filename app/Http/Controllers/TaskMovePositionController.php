<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Controller;

use Hiject\Core\Controller\AccessForbiddenException;
use Hiject\Formatter\BoardFormatter;
use Hiject\Model\TaskModel;

/**
 * Class TaskMovePositionController
 */
class TaskMovePositionController extends BaseController
{
    public function show()
    {
        $task = $this->getTask();

        $this->response->html($this->template->render('task_move_position/show', array(
            'task' => $task,
            'board' => BoardFormatter::getInstance($this->container)
                ->withProjectId($task['project_id'])
                ->withQuery($this->taskFinderModel->getExtendedQuery()
                    ->eq(TaskModel::TABLE.'.is_active', TaskModel::STATUS_OPEN)
                    ->neq(TaskModel::TABLE.'.id', $task['id'])
                )
                ->format()
        )));
    }

    public function save()
    {
        $task = $this->getTask();
        $values = $this->request->getJson();

        if (! $this->helper->projectRole->canMoveTask($task['project_id'], $task['column_id'], $values['column_id'])) {
            throw new AccessForbiddenException(e('You are not allowed to move this task.'));
        }

        $result = $this->taskPositionModel->movePosition(
            $task['project_id'],
            $task['id'],
            $values['column_id'],
            $values['position'],
            $values['swimlane_id']
        );

        $this->response->json(array('result' => $result));
    }
}
