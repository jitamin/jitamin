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
use Jitamin\Core\Controller\PageNotFoundException;

/**
 * Subtask controller.
 */
class SubtaskController extends BaseController
{
    /**
     * Creation form.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws AccessForbiddenException
     * @throws PageNotFoundException
     */
    public function create(array $values = [], array $errors = [])
    {
        $task = $this->getTask();

        if (empty($values)) {
            $values = $this->prepareValues($task);
        }

        $this->response->html($this->template->render('subtask/create', [
            'values'     => $values,
            'errors'     => $errors,
            'users_list' => $this->projectUserRoleModel->getAssignableUsersList($task['project_id']),
            'task'       => $task,
        ]));
    }

    /**
     * Prepare form values.
     *
     * @param array $task
     *
     * @return array
     */
    protected function prepareValues(array $task)
    {
        $values = [
            'task_id'         => $task['id'],
            'another_subtask' => $this->request->getIntegerParam('another_subtask', 0),
        ];

        $values = $this->hook->merge('controller:subtask:form:default', $values, ['default_values' => $values]);

        return $values;
    }

    /**
     * Validation and creation.
     */
    public function store()
    {
        $task = $this->getTask();
        $values = $this->request->getValues();

        list($valid, $errors) = $this->subtaskValidator->validateCreation($values);

        if ($valid) {
            if ($this->subtaskModel->create($values) !== false) {
                $this->flash->success(t('Sub-task added successfully.'));
            } else {
                $this->flash->failure(t('Unable to create your sub-task.'));
            }

            if (isset($values['another_subtask']) && $values['another_subtask'] == 1) {
                return $this->create(['project_id' => $task['project_id'], 'task_id' => $task['id'], 'another_subtask' => 1]);
            }

            return $this->response->redirect($this->helper->url->to('TaskController', 'show', ['project_id' => $task['project_id'], 'task_id' => $task['id']], 'subtasks'), true);
        }

        return $this->create($values, $errors);
    }

    /**
     * Edit form.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws AccessForbiddenException
     * @throws PageNotFoundException
     */
    public function edit(array $values = [], array $errors = [])
    {
        $task = $this->getTask();
        $subtask = $this->getSubtask();

        $this->response->html($this->template->render('subtask/edit', [
            'values'      => empty($values) ? $subtask : $values,
            'errors'      => $errors,
            'users_list'  => $this->projectUserRoleModel->getAssignableUsersList($task['project_id']),
            'status_list' => $this->subtaskModel->getStatusList(),
            'subtask'     => $subtask,
            'task'        => $task,
        ]));
    }

    /**
     * Update and validate a subtask.
     */
    public function update()
    {
        $task = $this->getTask();
        $this->getSubtask();

        $values = $this->request->getValues();
        list($valid, $errors) = $this->subtaskValidator->validateModification($values);

        if ($valid) {
            if ($this->subtaskModel->update($values)) {
                $this->flash->success(t('Sub-task updated successfully.'));
            } else {
                $this->flash->failure(t('Unable to update your sub-task.'));
            }

            return $this->response->redirect($this->helper->url->to('TaskController', 'show', ['project_id' => $task['project_id'], 'task_id' => $task['id']]), true);
        }

        return $this->edit($values, $errors);
    }

    /**
     * Confirmation dialog before removing a subtask.
     */
    public function confirm()
    {
        $task = $this->getTask();
        $subtask = $this->getSubtask();

        $this->response->html($this->template->render('subtask/remove', [
            'subtask' => $subtask,
            'task'    => $task,
        ]));
    }

    /**
     * Remove a subtask.
     */
    public function remove()
    {
        $this->checkCSRFParam();
        $task = $this->getTask();
        $subtask = $this->getSubtask();

        if ($this->subtaskModel->remove($subtask['id'])) {
            $this->flash->success(t('Sub-task removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this sub-task.'));
        }

        $this->response->redirect($this->helper->url->to('TaskController', 'show', ['project_id' => $task['project_id'], 'task_id' => $task['id']]), true);
    }

    /**
     * Move subtask position.
     */
    public function movePosition()
    {
        $project_id = $this->request->getIntegerParam('project_id');
        $task_id = $this->request->getIntegerParam('task_id');
        $values = $this->request->getJson();

        if (!empty($values) && $this->helper->user->hasProjectAccess('SubtaskController', 'movePosition', $project_id)) {
            $result = $this->subtaskPositionModel->changePosition($task_id, $values['subtask_id'], $values['position']);
            $this->response->json(['result' => $result]);
        } else {
            throw new AccessForbiddenException();
        }
    }
}
