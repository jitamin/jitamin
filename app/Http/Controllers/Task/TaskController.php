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
use Jitamin\Core\Controller\AccessForbiddenException;
use Jitamin\Core\Controller\PageNotFoundException;
use Jitamin\Filter\TaskIdExclusionFilter;
use Jitamin\Filter\TaskIdFilter;
use Jitamin\Filter\TaskProjectFilter;
use Jitamin\Filter\TaskProjectsFilter;
use Jitamin\Filter\TaskTitleFilter;
use Jitamin\Formatter\TaskAutoCompleteFormatter;
use Jitamin\Formatter\TaskGanttFormatter;
use Jitamin\Model\TaskModel;
use Jitamin\Model\UserMetadataModel;

/**
 * Task Controller.
 */
class TaskController extends Controller
{
    /**
     * Show list view for projects.
     */
    public function index()
    {
        $project = $this->getProject();
        $search = $this->helper->projectHeader->getSearchQuery($project);

        $paginator = $this->paginator
            ->setUrl('Task/TaskController', 'index', ['project_id' => $project['id']])
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
     * Show a task.
     */
    public function show()
    {
        $task = $this->getTask();
        $subtasks = $this->subtaskModel->getAll($task['id']);
        $commentSortingDirection = $this->userMetadataCacheDecorator->get(UserMetadataModel::KEY_COMMENT_SORTING_DIRECTION, 'ASC');

        $this->response->html($this->helper->layout->task('task/show', [
            'task'            => $task,
            'project'         => $this->projectModel->getById($task['project_id']),
            'files'           => $this->taskFileModel->getAllDocuments($task['id']),
            'images'          => $this->taskFileModel->getAllImages($task['id']),
            'comments'        => $this->commentModel->getAll($task['id'], $commentSortingDirection),
            'subtasks'        => $subtasks,
            'internal_links'  => $this->taskLinkModel->getAllGroupedByLabel($task['id']),
            'external_links'  => $this->taskExternalLinkModel->getAll($task['id']),
            'link_label_list' => $this->linkModel->getList(0, false),
            'tags'            => $this->taskTagModel->getList($task['id']),
        ]));
    }

    /**
     * Show Gantt chart for one project.
     */
    public function gantt()
    {
        $project = $this->getProject();
        $search = $this->helper->projectHeader->getSearchQuery($project);
        $sorting = $this->request->getStringParam('sorting', 'board');
        $filter = $this->taskLexer->build($search)->withFilter(new TaskProjectFilter($project['id']));

        if ($sorting === 'date') {
            $filter->getQuery()->asc(TaskModel::TABLE.'.date_started')->asc(TaskModel::TABLE.'.date_creation');
        } else {
            $filter->getQuery()->asc('column_position')->asc(TaskModel::TABLE.'.position');
        }

        $this->response->html($this->helper->layout->app('task/gantt', [
            'project'     => $project,
            'title'       => $project['name'],
            'description' => $this->helper->projectHeader->getDescription($project),
            'sorting'     => $sorting,
            'tasks'       => $filter->format(new TaskGanttFormatter($this->container)),
        ]));
    }

    /**
     * Public access (display a task).
     */
    public function readonly()
    {
        $project = $this->projectModel->getByToken($this->request->getStringParam('token'));

        if (empty($project)) {
            throw AccessForbiddenException::getInstance()->withoutLayout();
        }

        $task = $this->taskFinderModel->getDetails($this->request->getIntegerParam('task_id'));

        if (empty($task)) {
            throw PageNotFoundException::getInstance()->withoutLayout();
        }

        if ($task['project_id'] != $project['id']) {
            throw AccessForbiddenException::getInstance()->withoutLayout();
        }

        $this->response->html($this->helper->layout->app('task/public', [
            'project'      => $project,
            'comments'     => $this->commentModel->getAll($task['id']),
            'subtasks'     => $this->subtaskModel->getAll($task['id']),
            'links'        => $this->taskLinkModel->getAllGroupedByLabel($task['id']),
            'task'         => $task,
            'columns_list' => $this->columnModel->getList($task['project_id']),
            'colors_list'  => $this->colorModel->getList(),
            'tags'         => $this->taskTagModel->getList($task['id']),
            'title'        => $task['title'],
            'no_layout'    => true,
            'auto_refresh' => true,
            'not_editable' => true,
        ]));
    }

    /**
     * Display task analytics.
     */
    public function analytics()
    {
        $task = $this->getTask();

        $this->response->html($this->helper->layout->task('task/analytics', [
            'task'               => $task,
            'project'            => $this->projectModel->getById($task['project_id']),
            'lead_time'          => $this->taskAnalyticModel->getLeadTime($task),
            'cycle_time'         => $this->taskAnalyticModel->getCycleTime($task),
            'time_spent_columns' => $this->taskAnalyticModel->getTimeSpentByColumn($task),
            'tags'               => $this->taskTagModel->getList($task['id']),
        ]));
    }

    /**
     * Display the time tracking details.
     */
    public function timetracking()
    {
        $task = $this->getTask();

        $subtask_paginator = $this->paginator
            ->setUrl('Task/TaskController', 'timetracking', ['task_id' => $task['id'], 'project_id' => $task['project_id'], 'pagination' => 'subtasks'])
            ->setMax(15)
            ->setOrder('start')
            ->setDirection('DESC')
            ->setQuery($this->subtaskTimeTrackingModel->getTaskQuery($task['id']))
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'subtasks');

        $this->response->html($this->helper->layout->task('task/time_tracking_details', [
            'task'              => $task,
            'project'           => $this->projectModel->getById($task['project_id']),
            'subtask_paginator' => $subtask_paginator,
            'tags'              => $this->taskTagModel->getList($task['id']),
        ]));
    }

    /**
     * Display the task transitions.
     */
    public function transitions()
    {
        $task = $this->getTask();

        $this->response->html($this->helper->layout->task('task/transitions', [
            'task'        => $task,
            'project'     => $this->projectModel->getById($task['project_id']),
            'transitions' => $this->transitionModel->getAllByTask($task['id']),
            'tags'        => $this->taskTagModel->getList($task['id']),
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
            $this->response->redirect($this->helper->url->to('Project/Board/BoardController', 'show', ['project_id' => $project['id']]), true);
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
        $this->response->redirect($this->helper->url->to('Task/TaskController', 'show', ['project_id' => $task['project_id'], 'task_id' => $task['id']]));
    }

    /**
     * Save new task start date and due date.
     */
    public function set_date()
    {
        $this->getProject();
        $values = $this->request->getJson();

        $result = $this->taskModel->update([
            'id'           => $values['id'],
            'date_started' => strtotime($values['start']),
            'date_due'     => strtotime($values['end']),
        ]);

        if (!$result) {
            $this->response->json(['message' => 'Unable to save task'], 400);
        } else {
            $this->response->json(['message' => 'OK'], 201);
        }
    }

    /**
     * Display a form to edit a task.
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
            $this->response->redirect($this->helper->url->to('Task/TaskController', 'show', ['project_id' => $task['project_id'], 'task_id' => $task['id']]), true);
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

        $this->response->redirect($this->helper->url->to('Project/Board/BoardController', 'show', ['project_id' => $project['id']]), true);
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
            $this->create([
                'owner_id'     => $values['owner_id'],
                'color_id'     => $values['color_id'],
                'category_id'  => isset($values['category_id']) ? $values['category_id'] : 0,
                'column_id'    => $values['column_id'],
                'swimlane_id'  => isset($values['swimlane_id']) ? $values['swimlane_id'] : 0,
                'another_task' => 1,
            ]);
        } else {
            $this->response->redirect($this->helper->url->to('Project/Board/BoardController', 'show', ['project_id' => $project['id']]), true);
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
