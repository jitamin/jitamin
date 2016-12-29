<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Project;

use Jitamin\Controller\BaseController;
use Jitamin\Core\Controller\AccessForbiddenException;

/**
 * Class ProjectRoleRestrictionController.
 */
class ProjectRoleRestrictionController extends BaseController
{
    /**
     * Show form to create a new project restriction.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws AccessForbiddenException
     */
    public function create(array $values = [], array $errors = [])
    {
        $project = $this->getProject();
        $role_id = $this->request->getIntegerParam('role_id');
        $role = $this->projectRoleModel->getById($project['id'], $role_id);

        $this->response->html($this->template->render('project_role_restriction/create', [
            'project'      => $project,
            'role'         => $role,
            'values'       => $values + ['project_id' => $project['id'], 'role_id' => $role['role_id']],
            'errors'       => $errors,
            'restrictions' => $this->projectRoleRestrictionModel->getRules(),
        ]));
    }

    /**
     * Save new restriction.
     */
    public function store()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        $restriction_id = $this->projectRoleRestrictionModel->create(
            $project['id'],
            $values['role_id'],
            $values['rule']
        );

        if ($restriction_id !== false) {
            $this->flash->success(t('The project restriction has been created successfully.'));
        } else {
            $this->flash->failure(t('Unable to create this project restriction.'));
        }

        $this->response->redirect($this->helper->url->to('Project/ProjectRoleController', 'show', ['project_id' => $project['id']]));
    }

    /**
     * Confirm suppression.
     */
    public function confirm()
    {
        $project = $this->getProject();
        $restriction_id = $this->request->getIntegerParam('restriction_id');

        $this->response->html($this->helper->layout->project('project_role_restriction/remove', [
            'project'      => $project,
            'restriction'  => $this->projectRoleRestrictionModel->getById($project['id'], $restriction_id),
            'restrictions' => $this->projectRoleRestrictionModel->getRules(),
        ]));
    }

    /**
     * Remove a restriction.
     */
    public function remove()
    {
        $project = $this->getProject();
        $this->checkCSRFParam();
        $restriction_id = $this->request->getIntegerParam('restriction_id');

        if ($this->projectRoleRestrictionModel->remove($restriction_id)) {
            $this->flash->success(t('Project restriction removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this restriction.'));
        }

        $this->response->redirect($this->helper->url->to('Project/ProjectRoleController', 'show', ['project_id' => $project['id']]));
    }
}
