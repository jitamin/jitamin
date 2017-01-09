<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Manage;

use Jitamin\Controller\Controller;

/**
 * Class ProjectSettingsController.
 */
class ProjectSettingsController extends Controller
{
    /**
     * General edition (most common operations).
     *
     * @param array $values
     * @param array $errors
     */
    public function edit(array $values = [], array $errors = [])
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->project('manage/project_settings/edit', [
            'owners'  => $this->projectUserRoleModel->getAssignableUsersList($project['id'], true),
            'values'  => empty($values) ? $project : $values,
            'errors'  => $errors,
            'project' => $project,
            'views'   => $this->projectModel->getViews(),
            'title'   => t('Edit project'),
        ]));
    }

    /**
     * Change project description.
     *
     * @param array $values
     * @param array $errors
     */
    public function edit_description(array $values = [], array $errors = [])
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->project('manage/project_settings/edit_description', [
            'owners'  => $this->projectUserRoleModel->getAssignableUsersList($project['id'], true),
            'values'  => empty($values) ? $project : $values,
            'errors'  => $errors,
            'project' => $project,
            'title'   => t('Edit project'),
        ]));
    }

    /**
     * Validate and update a project.
     */
    public function update()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();
        $redirect = $this->request->getStringParam('redirect', 'edit');

        $values = $this->prepareValues($redirect, $project, $values);
        list($valid, $errors) = $this->projectValidator->validateModification($values);

        if ($valid) {
            if ($this->projectModel->update($values)) {
                $this->flash->success(t('Project updated successfully.'));

                return $this->response->redirect($this->helper->url->to('Manage/ProjectSettingsController', $redirect, ['project_id' => $project['id']]), true);
            } else {
                $this->flash->failure(t('Unable to update this project.'));
            }
        }

        return $this->$redirect($values, $errors);
    }

    /**
     * Public access management.
     */
    public function share()
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->project('manage/project_settings/share', [
            'project' => $project,
            'title'   => t('Public access'),
        ]));
    }

    /**
     * Change project sharing.
     *
     * @throws \Jitamin\Foundation\Controller\AccessForbiddenException
     * @throws \Jitamin\Foundation\Controller\PageNotFoundException
     */
    public function updateSharing()
    {
        $project = $this->getProject();
        $switch = $this->request->getStringParam('switch');

        if ($this->projectModel->{$switch.'PublicAccess'}($project['id'])) {
            $this->flash->success(t('Project updated successfully.'));
        } else {
            $this->flash->failure(t('Unable to update this project.'));
        }

        $this->response->redirect($this->helper->url->to('Manage/ProjectSettingsController', 'share', ['project_id' => $project['id']]));
    }

    /**
     * Integrations page.
     */
    public function integrations()
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->project('manage/project_settings/integrations', [
            'project'       => $project,
            'title'         => t('Integrations'),
            'webhook_token' => $this->settingModel->get('webhook_token'),
            'values'        => $this->projectMetadataModel->getAll($project['id']),
            'errors'        => [],
        ]));
    }

    /**
     * Update integrations.
     *
     * @throws \Jitamin\Foundation\Controller\PageNotFoundException
     */
    public function updateIntegrations()
    {
        $project = $this->getProject();

        $this->projectMetadataModel->save($project['id'], $this->request->getValues());
        $this->flash->success(t('Project updated successfully.'));
        $this->response->redirect($this->helper->url->to('Manage/ProjectSettingsController', 'integrations', ['project_id' => $project['id']]));
    }

    /**
     * Display project notifications.
     */
    public function notifications()
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->project('manage/project_settings/notifications', [
            'notifications' => $this->projectNotificationModel->readSettings($project['id']),
            'types'         => $this->projectNotificationTypeModel->getTypes(),
            'project'       => $project,
            'title'         => t('Notifications'),
        ]));
    }

    /**
     * Update notifications.
     *
     * @throws \Jitamin\Foundation\Controller\PageNotFoundException
     */
    public function updateNotifications()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        $this->projectNotificationModel->saveSettings($project['id'], $values);
        $this->flash->success(t('Project updated successfully.'));
        $this->response->redirect($this->helper->url->to('Manage/ProjectSettingsController', 'notifications', ['project_id' => $project['id']]));
    }

    /**
     * Duplicate a project.
     *
     * @author Antonio Rabelo
     * @author Michael Lüpkes
     */
    public function duplicate()
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->project('manage/project_settings/duplicate', [
            'project' => $project,
            'title'   => t('Clone this project'),
        ]));
    }

    /**
     * Do project duplication.
     */
    public function doDuplication()
    {
        $project = $this->getProject();
        $project_id = $this->projectDuplicationModel->duplicate($project['id'], array_keys($this->request->getValues()), $this->userSession->getId());

        if ($project_id !== false) {
            $this->flash->success(t('Project cloned successfully.'));
        } else {
            $this->flash->failure(t('Unable to clone this project.'));
        }

        $this->response->redirect($this->helper->url->to('Project/ProjectController', 'show', ['project_id' => $project_id]));
    }

    /**
     * Prepare form values.
     *
     * @param string $redirect
     * @param array  $project
     * @param array  $values
     *
     * @return array
     */
    protected function prepareValues($redirect, array $project, array $values)
    {
        if ($redirect === 'edit') {
            if (isset($values['is_private'])) {
                if (!$this->helper->user->hasProjectAccess('Project/ProjectController', 'create', $project['id'])) {
                    unset($values['is_private']);
                }
            } elseif ($project['is_private'] == 1 && !isset($values['is_private'])) {
                if ($this->helper->user->hasProjectAccess('Project/ProjectController', 'create', $project['id'])) {
                    $values += ['is_private' => 0];
                }
            }
        }

        return $values;
    }
}
