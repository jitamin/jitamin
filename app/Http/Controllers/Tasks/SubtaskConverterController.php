<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller;

/**
 * Class SubtaskConverterController.
 */
class SubtaskConverterController extends BaseController
{
    public function show()
    {
        $task = $this->getTask();
        $subtask = $this->getSubtask();

        $this->response->html($this->template->render('subtask_converter/show', [
            'subtask' => $subtask,
            'task'    => $task,
        ]));
    }

    public function save()
    {
        $project = $this->getProject();
        $subtask = $this->getSubtask();

        $task_id = $this->subtaskTaskConversionModel->convertToTask($project['id'], $subtask['id']);

        if ($task_id !== false) {
            $this->flash->success(t('Subtask converted to task successfully.'));
        } else {
            $this->flash->failure(t('Unable to convert the subtask.'));
        }

        $this->response->redirect($this->helper->url->to('TaskViewController', 'show', ['project_id' => $project['id'], 'task_id' => $task_id]), true);
    }
}
