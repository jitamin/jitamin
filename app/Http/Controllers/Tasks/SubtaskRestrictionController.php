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

use Jitamin\Model\SubtaskModel;

/**
 * Subtask Restriction.
 */
class SubtaskRestrictionController extends BaseController
{
    /**
     * Show popup.
     */
    public function show()
    {
        $task = $this->getTask();
        $subtask = $this->getSubtask();

        $this->response->html($this->template->render('subtask_restriction/show', [
            'status_list' => [
                SubtaskModel::STATUS_TODO => t('Todo'),
                SubtaskModel::STATUS_DONE => t('Done'),
            ],
            'subtask_inprogress' => $this->subtaskStatusModel->getSubtaskInProgress($this->userSession->getId()),
            'subtask'            => $subtask,
            'task'               => $task,
        ]));
    }

    /**
     * Change status of the in progress subtask and the other subtask.
     */
    public function store()
    {
        $task = $this->getTask();
        $subtask = $this->getSubtask();
        $values = $this->request->getValues();

        // Change status of the previous "in progress" subtask
        $this->subtaskModel->update([
            'id'     => $values['id'],
            'status' => $values['status'],
        ]);

        // Set the current subtask to "in progress"
        $this->subtaskModel->update([
            'id'     => $subtask['id'],
            'status' => SubtaskModel::STATUS_INPROGRESS,
        ]);

        $this->response->redirect($this->helper->url->to('TaskViewController', 'show', ['project_id' => $task['project_id'], 'task_id' => $task['id']]), true);
    }
}
