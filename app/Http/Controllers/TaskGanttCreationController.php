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
 * Class TaskGanttCreationController
 */
class TaskGanttCreationController extends BaseController
{
    /**
     * Simplified form to create a new task
     *
     * @access public
     * @param  array $values
     * @param  array $errors
     * @throws \Hiject\Core\Controller\PageNotFoundException
     */
    public function show(array $values = [], array $errors = [])
    {
        $project = $this->getProject();

        $values = $values + [
            'project_id' => $project['id'],
            'column_id' => $this->columnModel->getFirstColumnId($project['id']),
            'position' => 1
        ];

        $values = $this->hook->merge('controller:task:form:default', $values, ['default_values' => $values]);
        $values = $this->hook->merge('controller:gantt:task:form:default', $values, ['default_values' => $values]);

        $this->response->html($this->template->render('task_gantt_creation/show', [
            'project' => $project,
            'errors' => $errors,
            'values' => $values,
            'users_list' => $this->projectUserRoleModel->getAssignableUsersList($project['id'], true, false, true),
            'categories_list' => $this->categoryModel->getList($project['id']),
            'swimlanes_list' => $this->swimlaneModel->getList($project['id'], false, true),
        ]));
    }

    /**
     * Validate and save a new task
     *
     * @access public
     */
    public function save()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        list($valid, $errors) = $this->taskValidator->validateCreation($values);

        if ($valid && $this->taskCreationModel->create($values)) {
            $this->flash->success(t('Task created successfully.'));
            $this->response->redirect($this->helper->url->to('TaskGanttController', 'show', ['project_id' => $project['id']]));
        } else {
            $this->flash->failure(t('Unable to create your task.'));
            $this->show($values, $errors);
        }
    }
}
