<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Manage;

use Jitamin\Http\Controllers\Controller;

/**
 * Class ProjectStatusController.
 */
class ProjectStatusController extends Controller
{
    /**
     * Enable the project.
     */
    public function enable()
    {
        $project = $this->getProject();

        if ($this->request->isPost()) {
            $this->request->checkCSRFToken();
            if ($this->projectModel->enable($project['id'])) {
                $this->flash->success(t('Project activated successfully.'));
            } else {
                $this->flash->failure(t('Unable to activate this project.'));
            }

            return $this->response->redirect($this->helper->url->to('Project/ProjectController', 'show', ['project_id' => $project['id']]), true);
        }

        return $this->response->html($this->template->render('manage/project_status/enable', [
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

        if ($this->request->isPost()) {
            $this->request->checkCSRFToken();
            if ($this->projectModel->disable($project['id'])) {
                $this->flash->success(t('Project disabled successfully.'));
            } else {
                $this->flash->failure(t('Unable to disable this project.'));
            }

            $this->response->redirect($this->helper->url->to('Project/ProjectController', 'show', ['project_id' => $project['id']]), true);
        }

        return $this->response->html($this->template->render('manage/project_status/disable', [
            'project' => $project,
            'title'   => t('Project activation'),
        ]));
    }

    /**
     * Remove a project.
     */
    public function remove()
    {
        $project = $this->getProject();

        if ($this->request->isPost()) {
            $this->request->checkCSRFToken();
            if ($this->projectModel->remove($project['id'])) {
                $this->flash->success(t('Project removed successfully.'));
            } else {
                $this->flash->failure(t('Unable to remove this project.'));
            }

            return $this->response->redirect($this->helper->url->to('Manage/ProjectController', 'index'), true);
        }

        return $this->response->html($this->template->render('manage/project_status/remove', [
            'project' => $project,
            'title'   => t('Remove project'),
        ]));
    }
}
