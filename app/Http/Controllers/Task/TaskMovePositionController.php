<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Task;

use Jitamin\Controller\BaseController;
use Jitamin\Core\Controller\AccessForbiddenException;
use Jitamin\Formatter\BoardFormatter;
use Jitamin\Model\TaskModel;

/**
 * Class TaskMovePositionController.
 */
class TaskMovePositionController extends BaseController
{
    /**
     * Show position movement.
     */
    public function show()
    {
        $task = $this->getTask();

        $this->response->html($this->template->render('task_move_position/show', [
            'task'  => $task,
            'board' => BoardFormatter::getInstance($this->container)
                ->withProjectId($task['project_id'])
                ->withQuery($this->taskFinderModel->getExtendedQuery()
                    ->eq(TaskModel::TABLE.'.is_active', TaskModel::STATUS_OPEN)
                    ->neq(TaskModel::TABLE.'.id', $task['id'])
                )
                ->format(),
        ]));
    }

    /**
     * Save new position movement.
     */
    public function store()
    {
        $task = $this->getTask();
        $values = $this->request->getJson();

        if (!$this->helper->projectRole->canMoveTask($task['project_id'], $task['column_id'], $values['column_id'])) {
            throw new AccessForbiddenException(e('You are not allowed to move this task.'));
        }

        $result = $this->taskPositionModel->movePosition(
            $task['project_id'],
            $task['id'],
            $values['column_id'],
            $values['position'],
            $values['swimlane_id']
        );

        $this->response->json(['result' => $result]);
    }
}
