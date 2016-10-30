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

/**
 * Task Recurrence controller
 */
class TaskRecurrenceController extends BaseController
{
    /**
     * Edit recurrence form
     *
     * @access public
     * @param array $values
     * @param array $errors
     * @throws \Hiject\Core\Controller\AccessForbiddenException
     * @throws \Hiject\Core\Controller\PageNotFoundException
     */
    public function edit(array $values = array(), array $errors = array())
    {
        $task = $this->getTask();

        if (empty($values)) {
            $values = $task;
        }

        $this->response->html($this->template->render('task_recurrence/edit', array(
            'values' => $values,
            'errors' => $errors,
            'task' => $task,
            'recurrence_status_list' => $this->taskRecurrenceModel->getRecurrenceStatusList(),
            'recurrence_trigger_list' => $this->taskRecurrenceModel->getRecurrenceTriggerList(),
            'recurrence_timeframe_list' => $this->taskRecurrenceModel->getRecurrenceTimeframeList(),
            'recurrence_basedate_list' => $this->taskRecurrenceModel->getRecurrenceBasedateList(),
        )));
    }

    /**
     * Update recurrence form
     *
     * @access public
     */
    public function update()
    {
        $task = $this->getTask();
        $values = $this->request->getValues();

        list($valid, $errors) = $this->taskValidator->validateEditRecurrence($values);

        if ($valid) {
            if ($this->taskModificationModel->update($values)) {
                $this->flash->success(t('Task updated successfully.'));
            } else {
                $this->flash->failure(t('Unable to update your task.'));
            }

            return $this->response->redirect($this->helper->url->to('TaskViewController', 'show', array('project_id' => $task['project_id'], 'task_id' => $task['id'])), true);
        }

        return $this->edit($values, $errors);
    }
}
