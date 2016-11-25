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
 * Task Modification controller
 */
class TaskModificationController extends BaseController
{
    /**
     * Set automatically the start date
     *
     * @access public
     */
    public function start()
    {
        $task = $this->getTask();
        $this->taskModificationModel->update(array('id' => $task['id'], 'date_started' => time()));
        $this->response->redirect($this->helper->url->to('TaskViewController', 'show', ['project_id' => $task['project_id'], 'task_id' => $task['id']]));
    }

    /**
     * Display a form to edit a task
     *
     * @access public
     * @param array $values
     * @param array $errors
     * @throws \Hiject\Core\Controller\AccessForbiddenException
     * @throws \Hiject\Core\Controller\PageNotFoundException
     */
    public function edit(array $values = [], array $errors = [])
    {
        $task = $this->getTask();
        $project = $this->projectModel->getById($task['project_id']);

        if (empty($values)) {
            $values = $task;
        }

        $values = $this->hook->merge('controller:task:form:default', $values, ['default_values' => $values]);
        $values = $this->hook->merge('controller:task-modification:form:default', $values, ['default_values' => $values]);

        $this->response->html($this->template->render('task_modification/show', [
            'project' => $project,
            'values' => $values,
            'errors' => $errors,
            'task' => $task,
            'tags' => $this->taskTagModel->getList($task['id']),
            'users_list' => $this->projectUserRoleModel->getAssignableUsersList($task['project_id']),
            'categories_list' => $this->categoryModel->getList($task['project_id']),
        ]));
    }

    /**
     * Validate and update a task
     *
     * @access public
     */
    public function update()
    {
        $task = $this->getTask();
        $values = $this->request->getValues();

        list($valid, $errors) = $this->taskValidator->validateModification($values);

        if ($valid && $this->taskModificationModel->update($values)) {
            $this->flash->success(t('Task updated successfully.'));
            $this->response->redirect($this->helper->url->to('TaskViewController', 'show', ['project_id' => $task['project_id'], 'task_id' => $task['id']]), true);
        } else {
            $this->flash->failure(t('Unable to update your task.'));
            $this->edit($values, $errors);
        }
    }
}
