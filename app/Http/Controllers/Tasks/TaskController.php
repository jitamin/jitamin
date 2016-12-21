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
use Hiject\Filter\TaskIdExclusionFilter;
use Hiject\Filter\TaskIdFilter;
use Hiject\Filter\TaskProjectFilter;
use Hiject\Filter\TaskProjectsFilter;
use Hiject\Filter\TaskTitleFilter;
use Hiject\Formatter\TaskAutoCompleteFormatter;
use HIject\Model\TaskModel;

/**
 * Task Controller.
 */
class TaskController extends BaseController
{
    /**
     * Show list view for projects.
     */
    public function index()
    {
        $project = $this->getProject();
        $search = $this->helper->projectHeader->getSearchQuery($project);

        $paginator = $this->paginator
            ->setUrl('TaskController', 'index', ['project_id' => $project['id']])
            ->setMax(30)
            ->setOrder(TaskModel::TABLE.'.id')
            ->setDirection('DESC')
            ->setQuery($this->taskLexer
                ->build($search)
                ->withFilter(new TaskProjectFilter($project['id']))
                ->getQuery()
            )
            ->calculate();

        $this->response->html($this->helper->layout->app('task/index', [
            'project'     => $project,
            'title'       => $project['name'],
            'description' => $this->helper->projectHeader->getDescription($project),
            'paginator'   => $paginator,
        ]));
    }

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
            $this->response->redirect($this->helper->url->to('BoardController', 'show', ['project_id' => $project['id']]), true);
        } else {
            $task_id = $this->taskModel->create($values);
            $this->flash->success(t('Task created successfully.'));
            $this->afterSave($project, $values, $task_id);
        }
    }

    /**
     * Set automatically the start date.
     */
    public function start()
    {
        $task = $this->getTask();
        $this->taskModel->update(['id' => $task['id'], 'date_started' => time()]);
        $this->response->redirect($this->helper->url->to('TaskViewController', 'show', ['project_id' => $task['project_id'], 'task_id' => $task['id']]));
    }

    /**
     * Display a form to edit a task.
     *
     * @param array $values
     * @param array $errors
     *
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

        $this->response->html($this->template->render('task/edit', [
            'project'         => $project,
            'values'          => $values,
            'errors'          => $errors,
            'task'            => $task,
            'tags'            => $this->taskTagModel->getList($task['id']),
            'users_list'      => $this->projectUserRoleModel->getAssignableUsersList($task['project_id']),
            'categories_list' => $this->categoryModel->getList($task['project_id']),
        ]));
    }

    /**
     * Validate and update a task.
     */
    public function update()
    {
        $task = $this->getTask();
        $values = $this->request->getValues();

        list($valid, $errors) = $this->taskValidator->validateModification($values);

        if ($valid && $this->taskModel->update($values)) {
            $this->flash->success(t('Task updated successfully.'));
            $this->response->redirect($this->helper->url->to('TaskViewController', 'show', ['project_id' => $task['project_id'], 'task_id' => $task['id']]), true);
        } else {
            $this->flash->failure(t('Unable to update your task.'));
            $this->edit($values, $errors);
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

        $this->response->redirect($this->helper->url->to('BoardController', 'show', ['project_id' => $project['id']]), true);
    }

    /**
     * Task auto-completion (Ajax).
     */
    public function autocomplete()
    {
        $search = $this->request->getStringParam('term');
        $project_ids = $this->projectPermissionModel->getActiveProjectIds($this->userSession->getId());
        $exclude_task_id = $this->request->getIntegerParam('exclude_task_id');

        if (empty($project_ids)) {
            $this->response->json([]);
        } else {
            $filter = $this->taskQuery->withFilter(new TaskProjectsFilter($project_ids));

            if (!empty($exclude_task_id)) {
                $filter->withFilter(new TaskIdExclusionFilter([$exclude_task_id]));
            }

            if (ctype_digit($search)) {
                $filter->withFilter(new TaskIdFilter($search));
            } else {
                $filter->withFilter(new TaskTitleFilter($search));
            }

            $this->response->json($filter->format(new TaskAutoCompleteFormatter($this->container)));
        }
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
            $this->response->redirect($this->helper->url->to('BoardController', 'show', ['project_id' => $project['id']]), true);
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
