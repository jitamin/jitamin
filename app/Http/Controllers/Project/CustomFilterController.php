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
use Jitamin\Core\Controller\AccessForbiddenException;
use Jitamin\Core\Security\Role;

/**
 * Custom Filter Controller.
 */
class CustomFilterController extends Controller
{
    /**
     * Display list of filters.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws \Jitamin\Core\Controller\PageNotFoundException
     */
    public function index(array $values = [], array $errors = [])
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->project('project/custom_filter/index', [
            'values'         => $values + ['project_id' => $project['id']],
            'errors'         => $errors,
            'project'        => $project,
            'custom_filters' => $this->customFilterModel->getAll($project['id'], $this->userSession->getId()),
            'title'          => t('Custom filters'),
        ]));
    }

    /**
     * Save a new custom filter.
     */
    public function store()
    {
        $project = $this->getProject();

        $values = $this->request->getValues();
        $values['user_id'] = $this->userSession->getId();

        list($valid, $errors) = $this->customFilterValidator->validateCreation($values);

        if ($valid) {
            if ($this->customFilterModel->create($values) !== false) {
                $this->flash->success(t('Your custom filter have been created successfully.'));

                return $this->response->redirect($this->helper->url->to('Project/CustomFilterController', 'index', ['project_id' => $project['id']]));
            } else {
                $this->flash->failure(t('Unable to create your custom filter.'));
            }
        }

        return $this->index($values, $errors);
    }

    /**
     * Remove a custom filter.
     */
    public function remove()
    {
        $project = $this->getProject();
        $filter = $this->customFilterModel->getById($this->request->getIntegerParam('filter_id'));

        $this->checkPermission($project, $filter);

        if ($this->request->isPost()) {
            $this->request->checkCSRFToken();
            if ($this->customFilterModel->remove($filter['id'])) {
                $this->flash->success(t('Custom filter removed successfully.'));
            } else {
                $this->flash->failure(t('Unable to remove this custom filter.'));
            }

            return $this->response->redirect($this->helper->url->to('Project/CustomFilterController', 'index', ['project_id' => $project['id']]));
        }

        return $this->response->html($this->helper->layout->project('project/custom_filter/remove', [
            'project' => $project,
            'filter'  => $filter,
            'title'   => t('Remove a custom filter'),
        ]));
    }

    /**
     * Edit a custom filter (display the form).
     *
     * @param array $values
     * @param array $errors
     *
     * @throws AccessForbiddenException
     * @throws \Jitamin\Core\Controller\PageNotFoundException
     */
    public function edit(array $values = [], array $errors = [])
    {
        $project = $this->getProject();
        $filter = $this->customFilterModel->getById($this->request->getIntegerParam('filter_id'));

        $this->checkPermission($project, $filter);

        $this->response->html($this->helper->layout->project('project/custom_filter/edit', [
            'values'  => empty($values) ? $filter : $values,
            'errors'  => $errors,
            'project' => $project,
            'filter'  => $filter,
            'title'   => t('Edit custom filter'),
        ]));
    }

    /**
     * Edit a custom filter (validate the form and update the database).
     */
    public function update()
    {
        $project = $this->getProject();
        $filter = $this->customFilterModel->getById($this->request->getIntegerParam('filter_id'));

        $this->checkPermission($project, $filter);

        $values = $this->request->getValues();

        if (!isset($values['is_shared'])) {
            $values += ['is_shared' => 0];
        }

        if (!isset($values['append'])) {
            $values += ['append' => 0];
        }

        list($valid, $errors) = $this->customFilterValidator->validateModification($values);

        if ($valid) {
            if ($this->customFilterModel->update($values)) {
                $this->flash->success(t('Your custom filter have been updated successfully.'));

                return $this->response->redirect($this->helper->url->to('Project/CustomFilterController', 'index', ['project_id' => $project['id']]));
            } else {
                $this->flash->failure(t('Unable to update custom filter.'));
            }
        }

        return $this->edit($values, $errors);
    }

    /**
     * Check permission.
     *
     * @param array $project
     * @param array $filter
     *
     * @throws AccessForbiddenException
     */
    protected function checkPermission(array $project, array $filter)
    {
        $user_id = $this->userSession->getId();

        if ($filter['user_id'] != $user_id && ($this->projectUserRoleModel->getUserRole($project['id'], $user_id) === Role::PROJECT_MANAGER || !$this->userSession->isAdmin())) {
            throw new AccessForbiddenException();
        }
    }
}
