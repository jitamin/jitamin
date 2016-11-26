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
 * Class TaskBulkController
 */
class TaskBulkController extends BaseController
{
    /**
     * Show the form
     *
     * @param array $values
     * @param array $errors
     */
    public function show(array $values = [], array $errors = [])
    {
        $project = $this->getProject();

        if (empty($values)) {
            $values = [
                'swimlane_id' => $this->request->getIntegerParam('swimlane_id'),
                'column_id' => $this->request->getIntegerParam('column_id'),
                'project_id' => $project['id'],
            ];
        }

        $this->response->html($this->template->render('task_bulk/show', [
            'project' => $project,
            'values' => $values,
            'errors' => $errors,
            'users_list' => $this->projectUserRoleModel->getAssignableUsersList($project['id'], true, false, true),
            'colors_list' => $this->colorModel->getList(),
            'categories_list' => $this->categoryModel->getList($project['id']),
        ]));
    }

    /**
     * Save all tasks in the database
     */
    public function save()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();
        list($valid, $errors) = $this->taskValidator->validateBulkCreation($values);

        if (! $valid) {
            $this->show($values, $errors);
        } elseif (! $this->helper->projectRole->canCreateTaskInColumn($project['id'], $values['column_id'])) {
            $this->flash->failure(t('You cannot create tasks in this column.'));
            $this->response->redirect($this->helper->url->to('BoardViewController', 'show', ['project_id' => $project['id']]), true);
        } else {
            $this->createTasks($project, $values);
            $this->response->redirect($this->helper->url->to(
                'BoardViewController',
                'show',
                ['project_id' => $project['id']],
                'swimlane-'. $values['swimlane_id']
            ), true);
        }
    }

    /**
     * Create all tasks
     *
     * @param array $project
     * @param array $values
     */
    protected function createTasks(array $project, array $values)
    {
        $tasks = preg_split('/\r\n|[\r\n]/', $values['tasks']);

        foreach ($tasks as $title) {
            $title = trim($title);

            if (! empty($title)) {
                $this->taskCreationModel->create([
                    'title' => $title,
                    'column_id' => $values['column_id'],
                    'swimlane_id' => $values['swimlane_id'],
                    'category_id' => empty($values['category_id']) ? 0 : $values['category_id'],
                    'owner_id' => empty($values['owner_id']) ? 0 : $values['owner_id'],
                    'color_id' => $values['color_id'],
                    'project_id' => $project['id'],
                ]);
            }
        }
    }
}
