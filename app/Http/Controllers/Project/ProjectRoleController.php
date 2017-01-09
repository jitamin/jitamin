<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Project;

use Jitamin\Controller\Controller;
use Jitamin\Foundation\Controller\AccessForbiddenException;

/**
 * Class ProjectRoleController.
 */
class ProjectRoleController extends Controller
{
    /**
     * Show roles and permissions.
     */
    public function show()
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->project('project/role/show', [
            'project' => $project,
            'roles'   => $this->projectRoleModel->getAllWithRestrictions($project['id']),
            'title'   => t('Custom Project Roles'),
        ]));
    }

    /**
     * Show form to create new role.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws AccessForbiddenException
     */
    public function create(array $values = [], array $errors = [])
    {
        $project = $this->getProject();

        $this->response->html($this->template->render('project/role/create', [
            'project' => $project,
            'values'  => $values + ['project_id' => $project['id']],
            'errors'  => $errors,
        ]));
    }

    /**
     * Save new role.
     */
    public function store()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        list($valid, $errors) = $this->projectRoleValidator->validateCreation($values);

        if ($valid) {
            $role_id = $this->projectRoleModel->create($project['id'], $values['role']);

            if ($role_id !== false) {
                $this->flash->success(t('Your custom project role has been created successfully.'));
            } else {
                $this->flash->failure(t('Unable to create custom project role.'));
            }

            $this->response->redirect($this->helper->url->to('Project/ProjectRoleController', 'show', ['project_id' => $project['id']]));
        } else {
            $this->create($values, $errors);
        }
    }

    /**
     * Show form to change existing role.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws AccessForbiddenException
     */
    public function edit(array $values = [], array $errors = [])
    {
        $project = $this->getProject();
        $role = $this->getRole($project['id']);

        if (empty($values)) {
            $values = $role;
        }

        $this->response->html($this->template->render('project/role/edit', [
            'role'    => $role,
            'project' => $project,
            'values'  => $values,
            'errors'  => $errors,
        ]));
    }

    /**
     * Update role.
     */
    public function update()
    {
        $project = $this->getProject();
        $role_id = $this->request->getIntegerParam('role_id');
        $role = $this->projectRoleModel->getById($project['id'], $role_id);

        $values = $this->request->getValues();

        list($valid, $errors) = $this->projectRoleValidator->validateModification($values);

        if ($valid) {
            if ($this->projectRoleModel->update($role['role_id'], $project['id'], $values['role'])) {
                $this->flash->success(t('Your custom project role has been updated successfully.'));
            } else {
                $this->flash->failure(t('Unable to update custom project role.'));
            }

            $this->response->redirect($this->helper->url->to('Project/ProjectRoleController', 'show', ['project_id' => $project['id']]));
        } else {
            $this->edit($values, $errors);
        }
    }

    /**
     * Remove a custom role.
     */
    public function remove()
    {
        $project = $this->getProject();
        $role_id = $this->request->getIntegerParam('role_id');

        if ($this->request->isPost()) {
            $this->request->checkCSRFToken();
            if ($this->projectRoleModel->remove($project['id'], $role_id)) {
                $this->flash->success(t('Custom project role removed successfully.'));
            } else {
                $this->flash->failure(t('Unable to remove this project role.'));
            }

            return $this->response->redirect($this->helper->url->to('Project/ProjectRoleController', 'show', ['project_id' => $project['id']]));
        }

        $role = $this->projectRoleModel->getById($project['id'], $role_id);

        return $this->response->html($this->helper->layout->project('project/role/remove', [
            'project' => $project,
            'role'    => $role,
        ]));
    }
}
