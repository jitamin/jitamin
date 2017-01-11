<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Admin;

use Jitamin\Http\Controllers\Controller;

/**
 * User Status Controller.
 */
class UserStatusController extends Controller
{
    /**
     * Remove a user.
     */
    public function remove()
    {
        $user = $this->getUser();

        if ($this->request->isPost()) {
            $this->request->checkCSRFToken();
            if ($this->userModel->remove($user['id'])) {
                $this->flash->success(t('User removed successfully.'));
            } else {
                $this->flash->failure(t('Unable to remove this user.'));
            }

            return $this->response->redirect($this->helper->url->to('Admin/UserController', 'index'));
        }

        return $this->response->html($this->helper->layout->admin('admin/user_status/remove', [
            'user' => $user,
        ]));
    }

    /**
     * Enable a user.
     */
    public function enable()
    {
        $user = $this->getUser();

        if ($this->request->isPost()) {
            $this->request->checkCSRFToken();
            if ($this->userModel->enable($user['id'])) {
                $this->flash->success(t('User activated successfully.'));
            } else {
                $this->flash->failure(t('Unable to enable this user.'));
            }

            return $this->response->redirect($this->helper->url->to('Admin/UserController', 'index'));
        }

        return $this->response->html($this->helper->layout->admin('admin/user_status/enable', [
            'user' => $user,
        ]));
    }

    /**
     * Disable a user.
     */
    public function disable()
    {
        $user = $this->getUser();

        if ($this->request->isPost()) {
            $this->request->checkCSRFToken();
            if ($this->userModel->disable($user['id'])) {
                $this->flash->success(t('User disabled successfully.'));
            } else {
                $this->flash->failure(t('Unable to disable this user.'));
            }

            return $this->response->redirect($this->helper->url->to('Admin/UserController', 'index'));
        }

        return $this->response->html($this->helper->layout->admin('admin/user_status/disable', [
            'user' => $user,
        ]));
    }
}
