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

use Jitamin\Controller\BaseController;

/**
 * Class ProjectStatusController.
 */
class ProjectStatusController extends BaseController
{
    /**
     * Enable a project (confirmation dialog box).
     */
    public function confirmEnable()
    {
        $project = $this->getProject();

        $this->response->html($this->template->render('project_status/enable', [
            'project' => $project,
            'title'   => t('Project activation'),
        ]));
    }

    /**
     * Enable the project.
     */
    public function enable()
    {
        $project = $this->getProject();
        $this->checkCSRFParam();

        if ($this->projectModel->enable($project['id'])) {
            $this->flash->success(t('Project activated successfully.'));
        } else {
            $this->flash->failure(t('Unable to activate this project.'));
        }

        $this->response->redirect($this->helper->url->to('Project/ProjectSettingsController', 'show', ['project_id' => $project['id']]), true);
    }

    /**
     * Disable a project (confirmation dialog box).
     */
    public function confirmDisable()
    {
        $project = $this->getProject();

        $this->response->html($this->template->render('project_status/disable', [
            'project' => $project,
            'title'   => t('Project activation'),
        ]));
    }

    /**
     * Disable a project.
     */
    public function disable()
    {
        $project = $this->getProject();
        $this->checkCSRFParam();

        if ($this->projectModel->disable($project['id'])) {
            $this->flash->success(t('Project disabled successfully.'));
        } else {
            $this->flash->failure(t('Unable to disable this project.'));
        }

        $this->response->redirect($this->helper->url->to('Project/ProjectSettingsController', 'show', ['project_id' => $project['id']]), true);
    }

    /**
     * Remove a project (confirmation dialog box).
     */
    public function confirmRemove()
    {
        $project = $this->getProject();

        $this->response->html($this->template->render('project_status/remove', [
            'project' => $project,
            'title'   => t('Remove project'),
        ]));
    }

    /**
     * Remove a project.
     */
    public function remove()
    {
        $project = $this->getProject();
        $this->checkCSRFParam();

        if ($this->projectModel->remove($project['id'])) {
            $this->flash->success(t('Project removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this project.'));
        }

        $this->response->redirect($this->helper->url->to('Project/ProjectController', 'index'), true);
    }
}
