<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Auth;

use Jitamin\Controller\Controller;
use Jitamin\Core\Controller\AccessForbiddenException;
use Jitamin\Model\UserModel;

/**
 * Password Reset Controller.
 */
class PasswordResetController extends Controller
{
    /**
     * Show the form to reset the password.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws \Jitamin\Core\Controller\BaseException
     */
    public function create(array $values = [], array $errors = [])
    {
        $this->checkActivation();

        $this->response->html($this->helper->layout->app('auth/passwords/create', [
            'errors'    => $errors,
            'values'    => $values,
            'no_layout' => true,
        ]));
    }

    /**
     * Validate and send the email.
     */
    public function store()
    {
        $this->checkActivation();

        $values = $this->request->getValues();
        list($valid, $errors) = $this->passwordResetValidator->validateCreation($values);

        if ($valid) {
            $this->sendEmail($values['username']);
            $this->response->redirect($this->helper->url->to('Auth/AuthController', 'login'));
        } else {
            $this->create($values, $errors);
        }
    }

    /**
     * Show the form to set a new password.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws \Jitamin\Core\Controller\BaseException
     */
    public function change(array $values = [], array $errors = [])
    {
        $this->checkActivation();

        $token = $this->request->getStringParam('token');
        $user_id = $this->passwordResetModel->getUserIdByToken($token);

        if ($user_id !== false) {
            $this->response->html($this->helper->layout->app('auth/passwords/change', [
                'token'     => $token,
                'errors'    => $errors,
                'values'    => $values,
                'no_layout' => true,
            ]));
        } else {
            $this->response->redirect($this->helper->url->to('Auth/AuthController', 'login'));
        }
    }

    /**
     * Set the new password.
     */
    public function update()
    {
        $this->checkActivation();

        $token = $this->request->getStringParam('token');
        $values = $this->request->getValues();
        list($valid, $errors) = $this->passwordResetValidator->validateModification($values);

        if ($valid) {
            $user_id = $this->passwordResetModel->getUserIdByToken($token);

            if ($user_id !== false) {
                $this->userModel->update(['id' => $user_id, 'password' => $values['password']]);
                $this->passwordResetModel->disable($user_id);
            }

            return $this->response->redirect($this->helper->url->to('Auth/AuthController', 'login'));
        }

        return $this->change($values, $errors);
    }

    /**
     * Send the email.
     *
     * @param string $username
     */
    protected function sendEmail($username)
    {
        $user = $this->db->table(UserModel::TABLE)
            ->eq(strpos($username, '@') === false ? 'username' : 'email', $username)
            ->findOne();

        if (!$user || !$user['email']) {
            return false;
        }

        $token = $this->passwordResetModel->create($user['id']);

        if ($token !== false) {
            $this->emailClient->send(
                $user['email'],
                $user['name'] ?: $user['username'],
                t('Password Reset for Jitamin'),
                $this->template->render('auth/passwords/email', ['token' => $token])
            );
        }
    }

    /**
     * Check feature availability.
     */
    protected function checkActivation()
    {
        if ($this->settingModel->get('password_reset', 0) == 0) {
            throw AccessForbiddenException::getInstance()->withoutLayout();
        }
    }
}
