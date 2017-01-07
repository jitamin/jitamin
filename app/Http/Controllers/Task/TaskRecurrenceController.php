<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Task;

use Jitamin\Controller\Controller;

/**
 * Task Recurrence controller.
 */
class TaskRecurrenceController extends Controller
{
    /**
     * Edit recurrence form.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws \Jitamin\Core\Controller\AccessForbiddenException
     * @throws \Jitamin\Core\Controller\PageNotFoundException
     */
    public function edit(array $values = [], array $errors = [])
    {
        $task = $this->getTask();

        if (empty($values)) {
            $values = $task;
        }

        $this->response->html($this->template->render('task/recurrence/edit', [
            'values'                    => $values,
            'errors'                    => $errors,
            'task'                      => $task,
            'recurrence_status_list'    => $this->taskRecurrenceModel->getRecurrenceStatusList(),
            'recurrence_trigger_list'   => $this->taskRecurrenceModel->getRecurrenceTriggerList(),
            'recurrence_timeframe_list' => $this->taskRecurrenceModel->getRecurrenceTimeframeList(),
            'recurrence_basedate_list'  => $this->taskRecurrenceModel->getRecurrenceBasedateList(),
        ]));
    }

    /**
     * Update recurrence form.
     */
    public function update()
    {
        $task = $this->getTask();
        $values = $this->request->getValues();

        list($valid, $errors) = $this->taskValidator->validateEditRecurrence($values);

        if ($valid) {
            if ($this->taskModel->update($values)) {
                $this->flash->success(t('Task updated successfully.'));
            } else {
                $this->flash->failure(t('Unable to update your task.'));
            }

            return $this->response->redirect($this->helper->url->to('Task/TaskController', 'show', ['project_id' => $task['project_id'], 'task_id' => $task['id']]), true);
        }

        return $this->edit($values, $errors);
    }
}
