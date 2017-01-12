<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Profile;

use Jitamin\Foundation\Security\Token;
use Jitamin\Http\Controllers\Controller;

/**
 * Class HistoryController.
 */
class HistoryController extends Controller
{
    /**
     * Display timesheet.
     */
    public function timesheet()
    {
        $user = $this->getUser();

        $subtask_paginator = $this->paginator
            ->setUrl('Profile/HistoryController', 'timesheet', ['user_id' => $user['id'], 'pagination' => 'subtasks'])
            ->setMax(20)
            ->setOrder('start')
            ->setDirection('DESC')
            ->setQuery($this->subtaskTimeTrackingModel->getUserQuery($user['id']))
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'subtasks');

        $this->response->html($this->helper->layout->profile('profile/history/timesheet', [
            'subtask_paginator' => $subtask_paginator,
            'user'              => $user,
        ], 'profile/history/_partials/subnav'));
    }

    /**
     * Display last password reset.
     */
    public function passwordReset()
    {
        $user = $this->getUser();
        $this->response->html($this->helper->layout->profile('profile/history/password_reset', [
            'tokens' => $this->passwordResetModel->getAll($user['id']),
            'user'   => $user,
        ], 'profile/history/_partials/subnav'));
    }

    /**
     * Display last connections.
     */
    public function lastLogin()
    {
        $user = $this->getUser();
        $this->response->html($this->helper->layout->profile('profile/history/last', [
            'last_logins' => $this->lastLoginModel->getAll($user['id']),
            'user'        => $user,
        ], 'profile/history/_partials/subnav'));
    }

    /**
     * Display user sessions.
     */
    public function sessions()
    {
        $user = $this->getUser();
        $this->response->html($this->helper->layout->profile('profile/history/sessions', [
            'sessions' => $this->rememberMeSessionModel->getAll($user['id']),
            'user'     => $user,
        ], 'profile/history/_partials/subnav'));
    }

    /**
     * Remove a "RememberMe" token.
     */
    public function removeSession()
    {
        $user = $this->getUser();
        $this->rememberMeSessionModel->remove($this->request->getIntegerParam('id'));
        $this->response->redirect($this->helper->url->to('Profile/HistoryController', 'sessions', ['user_id' => $user['id']]));
    }
}
