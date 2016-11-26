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

use Hiject\Core\Controller\PageNotFoundException;
use Hiject\Model\ProjectModel;

/**
 * Class UserViewController
 */
class UserViewController extends BaseController
{
    /**
     * Public user profile
     *
     * @access public
     * @throws PageNotFoundException
     */
    public function profile()
    {
        $user = $this->userModel->getById($this->request->getIntegerParam('user_id'));

        if (empty($user)) {
            throw new PageNotFoundException();
        }

        $this->response->html($this->helper->layout->app('user_view/profile', [
            'title' => $user['name'] ?: $user['username'],
            'user'  => $user,
        ]));
    }

    /**
     * Display user information
     *
     * @access public
     */
    public function show()
    {
        $user = $this->getUser();
        $this->response->html($this->helper->layout->user('user_view/show', [
            'user'      => $user,
            'timezones' => $this->timezoneModel->getTimezones(true),
            'languages' => $this->languageModel->getLanguages(true),
        ]));
    }

    /**
     * Display timesheet
     *
     * @access public
     */
    public function timesheet()
    {
        $user = $this->getUser();

        $subtask_paginator = $this->paginator
            ->setUrl('UserViewController', 'timesheet', ['user_id' => $user['id'], 'pagination' => 'subtasks'])
            ->setMax(20)
            ->setOrder('start')
            ->setDirection('DESC')
            ->setQuery($this->subtaskTimeTrackingModel->getUserQuery($user['id']))
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'subtasks');

        $this->response->html($this->helper->layout->user('user_view/timesheet', [
            'subtask_paginator' => $subtask_paginator,
            'user'              => $user,
        ]));
    }

    /**
     * Display last password reset
     *
     * @access public
     */
    public function passwordReset()
    {
        $user = $this->getUser();
        $this->response->html($this->helper->layout->user('user_view/password_reset', [
            'tokens' => $this->passwordResetModel->getAll($user['id']),
            'user'   => $user,
        ]));
    }

    /**
     * Display last connections
     *
     * @access public
     */
    public function lastLogin()
    {
        $user = $this->getUser();
        $this->response->html($this->helper->layout->user('user_view/last', [
            'last_logins' => $this->lastLoginModel->getAll($user['id']),
            'user'        => $user,
        ]));
    }

    /**
     * Display user sessions
     *
     * @access public
     */
    public function sessions()
    {
        $user = $this->getUser();
        $this->response->html($this->helper->layout->user('user_view/sessions', [
            'sessions' => $this->rememberMeSessionModel->getAll($user['id']),
            'user'     => $user,
        ]));
    }

    /**
     * Remove a "RememberMe" token
     *
     * @access public
     */
    public function removeSession()
    {
        $this->checkCSRFParam();
        $user = $this->getUser();
        $this->rememberMeSessionModel->remove($this->request->getIntegerParam('id'));
        $this->response->redirect($this->helper->url->to('UserViewController', 'sessions', ['user_id' => $user['id']]));
    }

    /**
     * Display user notifications
     *
     * @access public
     */
    public function notifications()
    {
        $user = $this->getUser();

        if ($this->request->isPost()) {
            $values = $this->request->getValues();
            $this->userNotificationModel->saveSettings($user['id'], $values);
            $this->flash->success(t('User updated successfully.'));
            return $this->response->redirect($this->helper->url->to('UserViewController', 'notifications', ['user_id' => $user['id']]));
        }

        return $this->response->html($this->helper->layout->user('user_view/notifications', [
            'projects'      => $this->projectUserRoleModel->getProjectsByUser($user['id'], [ProjectModel::ACTIVE]),
            'notifications' => $this->userNotificationModel->readSettings($user['id']),
            'types'         => $this->userNotificationTypeModel->getTypes(),
            'filters'       => $this->userNotificationFilterModel->getFilters(),
            'user'          => $user,
        ]));
    }

    /**
     * Display user integrations
     *
     * @access public
     */
    public function integrations()
    {
        $user = $this->getUser();

        if ($this->request->isPost()) {
            $values = $this->request->getValues();
            $this->userMetadataModel->save($user['id'], $values);
            $this->flash->success(t('User updated successfully.'));
            $this->response->redirect($this->helper->url->to('UserViewController', 'integrations', ['user_id' => $user['id']]));
        }

        $this->response->html($this->helper->layout->user('user_view/integrations', [
            'user'   => $user,
            'values' => $this->userMetadataModel->getAll($user['id']),
        ]));
    }

    /**
     * Display external accounts
     *
     * @access public
     */
    public function external()
    {
        $user = $this->getUser();
        $this->response->html($this->helper->layout->user('user_view/external', [
            'last_logins' => $this->lastLoginModel->getAll($user['id']),
            'user'        => $user,
        ]));
    }

    /**
     * Public access management
     *
     * @access public
     */
    public function share()
    {
        $user = $this->getUser();
        $switch = $this->request->getStringParam('switch');

        if ($switch === 'enable' || $switch === 'disable') {
            $this->checkCSRFParam();

            if ($this->userModel->{$switch . 'PublicAccess'}($user['id'])) {
                $this->flash->success(t('User updated successfully.'));
            } else {
                $this->flash->failure(t('Unable to update this user.'));
            }

            return $this->response->redirect($this->helper->url->to('UserViewController', 'share', ['user_id' => $user['id']]));
        }

        return $this->response->html($this->helper->layout->user('user_view/share', [
            'user'  => $user,
            'title' => t('Public access'),
        ]));
    }
}
