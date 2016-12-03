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

use Hiject\Core\Controller\PageNotFoundException;

/**
 * Task Controller.
 */
class TaskController extends BaseController
{
    /**
     * Display a form to create a new task.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws PageNotFoundException
     */
    public function create(array $values = [], array $errors = [])
    {
        $project = $this->getProject();
        $swimlanes_list = $this->swimlaneModel->getList($project['id'], false, true);

        if (empty($values)) {
            $values = $this->prepareValues($swimlanes_list);
        }

        $values = $this->hook->merge('controller:task:form:default', $values, ['default_values' => $values]);
        $values = $this->hook->merge('controller:task-creation:form:default', $values, ['default_values' => $values]);

        $this->response->html($this->template->render('task/create', [
            'project'         => $project,
            'errors'          => $errors,
            'values'          => $values + ['project_id' => $project['id']],
            'columns_list'    => $this->columnModel->getList($project['id']),
            'users_list'      => $this->projectUserRoleModel->getAssignableUsersList($project['id'], true, false, true),
            'categories_list' => $this->categoryModel->getList($project['id']),
            'swimlanes_list'  => $swimlanes_list,
        ]));
    }

    /**
     * Validate and store a new task.
     */
    public function store()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        list($valid, $errors) = $this->taskValidator->validateCreation($values);

        if (!$valid) {
            $this->flash->failure(t('Unable to create your task.'));
            $this->show($values, $errors);
        } elseif (!$this->helper->projectRole->canCreateTaskInColumn($project['id'], $values['column_id'])) {
            $this->flash->failure(t('You cannot create tasks in this column.'));
            $this->response->redirect($this->helper->url->to('BoardViewController', 'show', ['project_id' => $project['id']]), true);
        } else {
            $task_id = $this->taskCreationModel->create($values);
            $this->flash->success(t('Task created successfully.'));
            $this->afterSave($project, $values, $task_id);
        }
    }

    /**
     * Duplicate created tasks to multiple projects.
     *
     * @throws PageNotFoundException
     */
    public function duplicateProjects()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        if (isset($values['project_ids'])) {
            foreach ($values['project_ids'] as $project_id) {
                $this->taskProjectDuplicationModel->duplicateToProject($values['task_id'], $project_id);
            }
        }

        $this->response->redirect($this->helper->url->to('BoardViewController', 'show', ['project_id' => $project['id']]), true);
    }

    /**
     * Executed after the task is saved.
     *
     * @param array $project
     * @param array $values
     * @param int   $task_id
     */
    protected function afterSave(array $project, array &$values, $task_id)
    {
        if (isset($values['duplicate_multiple_projects']) && $values['duplicate_multiple_projects'] == 1) {
            $this->chooseProjects($project, $task_id);
        } elseif (isset($values['another_task']) && $values['another_task'] == 1) {
            $this->show([
                'owner_id'     => $values['owner_id'],
                'color_id'     => $values['color_id'],
                'category_id'  => isset($values['category_id']) ? $values['category_id'] : 0,
                'column_id'    => $values['column_id'],
                'swimlane_id'  => isset($values['swimlane_id']) ? $values['swimlane_id'] : 0,
                'another_task' => 1,
            ]);
        } else {
            $this->response->redirect($this->helper->url->to('BoardViewController', 'show', ['project_id' => $project['id']]), true);
        }
    }

    /**
     * Prepare form values.
     *
     * @param array $swimlanes_list
     *
     * @return array
     */
    protected function prepareValues(array $swimlanes_list)
    {
        $values = [
            'swimlane_id' => $this->request->getIntegerParam('swimlane_id', key($swimlanes_list)),
            'column_id'   => $this->request->getIntegerParam('column_id'),
            'color_id'    => $this->colorModel->getDefaultColor(),
            'owner_id'    => $this->userSession->getId(),
        ];

        return $values;
    }

    /**
     * Choose projects.
     *
     * @param array $project
     * @param int   $task_id
     */
    protected function chooseProjects(array $project, $task_id)
    {
        $task = $this->taskFinderModel->getById($task_id);
        $projects = $this->projectUserRoleModel->getActiveProjectsByUser($this->userSession->getId());
        unset($projects[$project['id']]);

        $this->response->html($this->template->render('task/duplicate_projects', [
            'project'       => $project,
            'task'          => $task,
            'projects_list' => $projects,
            'values'        => ['task_id' => $task['id']],
        ]));
    }
}
