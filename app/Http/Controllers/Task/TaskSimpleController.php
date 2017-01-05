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

use Jitamin\Controller\BaseController;

/**
 * Class TaskSimpleController.
 */
class TaskSimpleController extends BaseController
{
    /**
     * Show the creation form.
     *
     * @param array $values
     * @param array $errors
     */
    public function create(array $values = [], array $errors = [])
    {
        $projects = $this->projectUserRoleModel->getActiveProjectsByUser($this->userSession->getId());

        $this->response->html($this->template->render('task/create_simple', [
            'values'          => $values,
            'errors'          => $errors,
            'projects'        => $projects,
        ]));
    }

    /**
     * Save all tasks in the database.
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
