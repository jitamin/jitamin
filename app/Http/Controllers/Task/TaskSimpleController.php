<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Task;

use Jitamin\Http\Controllers\Controller;

/**
 * Class TaskSimpleController.
 */
class TaskSimpleController extends Controller
{
    /**
     * Show the creation form.
     *
     * @param array $values
     * @param array $errors
     */
    public function create(array $values = [], array $errors = [])
    {
        $values['project_id'] = $this->request->getIntegerParam('project_id', 0);
        $values['column_id'] = $this->request->getIntegerParam('column_id', 0);
        $values['swimlane_id'] = $this->request->getIntegerParam('swimlane_id', 0);

        $projects = $this->projectUserRoleModel->getActiveProjectsByUser($this->userSession->getId());

        $this->response->html($this->template->render('task/create_simple', [
            'values'   => $values,
            'errors'   => $errors,
            'projects' => $projects,
        ]));
    }

    /**
     * Validate and store a new simple task.
     */
    public function store()
    {
        $values = $this->request->getValues();
        $project = $this->projectModel->getByIdWithOwner($values['project_id']);
        if (empty($project)) {
            throw new PageNotFoundException();
        }

        list($valid, $errors) = $this->taskValidator->validateCreation($values);

        if (!$valid) {
            $this->show($values, $errors);
        } else {
            $this->taskModel->create([
                    'title'       => $values['title'],
                    'project_id'  => $project['id'],
                    'column_id'   => $values['column_id'],
                    'swimlane_id' => $values['swimlane_id'],
                    'owner_id'    => $this->userSession->getId(),
                ]);
            $this->flash->success(t('Task created successfully.'));
            $this->response->redirect($this->helper->url->to(
                'Project/Board/BoardController',
                'show',
                ['project_id' => $project['id']],
                ''
            ), false);
        }
    }
}
