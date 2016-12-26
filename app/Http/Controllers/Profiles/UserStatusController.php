<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller;

/**
 * User Status Controller.
 */
class UserStatusController extends BaseController
{
    /**
     * Confirm remove a user.
     */
    public function confirmRemove()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->profile('user_status/remove', [
            'user' => $user,
        ]));
    }

    /**
     * Remove a user.
     */
    public function remove()
    {
        $user = $this->getUser();
        $this->checkCSRFParam();

        if ($this->userModel->remove($user['id'])) {
            $this->flash->success(t('User removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this user.'));
        }

        $this->response->redirect($this->helper->url->to('UserController', 'index'));
    }

    /**
     * Confirm enable a user.
     */
    public function confirmEnable()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->profile('user_status/enable', [
            'user' => $user,
        ]));
    }

    /**
     * Enable a user.
     */
    public function enable()
    {
        $user = $this->getUser();
        $this->checkCSRFParam();

        if ($this->userModel->enable($user['id'])) {
            $this->flash->success(t('User activated successfully.'));
        } else {
            $this->flash->failure(t('Unable to enable this user.'));
        }

        $this->response->redirect($this->helper->url->to('UserController', 'index'));
    }

    /**
     * Confirm disable a user.
     */
    public function confirmDisable()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->profile('user_status/disable', [
            'user' => $user,
        ]));
    }

    /**
     * Disable a user.
     */
    public function disable()
    {
        $user = $this->getUser();
        $this->checkCSRFParam();

        if ($this->userModel->disable($user['id'])) {
            $this->flash->success(t('User disabled successfully.'));
        } else {
            $this->flash->failure(t('Unable to disable this user.'));
        }

        $this->response->redirect($this->helper->url->to('UserController', 'index'));
    }
}
