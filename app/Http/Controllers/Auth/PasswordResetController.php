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

use Hiject\Core\Controller\AccessForbiddenException;

/**
 * Password Reset Controller.
 */
class PasswordResetController extends BaseController
{
    /**
     * Show the form to reset the password.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws \Hiject\Core\Controller\BaseException
     */
    public function create(array $values = [], array $errors = [])
    {
        $this->checkActivation();

        $this->response->html($this->helper->layout->app('password_reset/create', [
            'errors'    => $errors,
            'values'    => $values,
            'no_layout' => true,
        ]));
    }

    /**
     * Validate and send the email.
     */
    public function save()
    {
        $this->checkActivation();

        $values = $this->request->getValues();
        list($valid, $errors) = $this->passwordResetValidator->validateCreation($values);

        if ($valid) {
            $this->sendEmail($values['username']);
            $this->response->redirect($this->helper->url->to('AuthController', 'login'));
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
     * @throws \Hiject\Core\Controller\BaseException
     */
    public function change(array $values = [], array $errors = [])
    {
        $this->checkActivation();

        $token = $this->request->getStringParam('token');
        $user_id = $this->passwordResetModel->getUserIdByToken($token);

        if ($user_id !== false) {
            $this->response->html($this->helper->layout->app('password_reset/change', [
                'token'     => $token,
                'errors'    => $errors,
                'values'    => $values,
                'no_layout' => true,
            ]));
        } else {
            $this->response->redirect($this->helper->url->to('AuthController', 'login'));
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

            return $this->response->redirect($this->helper->url->to('AuthController', 'login'));
        }

        return $this->change($values, $errors);
    }

    /**
     * Send the email.
     *
     * @param string $username
     */
    private function sendEmail($username)
    {
        $token = $this->passwordResetModel->create($username);

        if ($token !== false) {
            $user = $this->userModel->getByUsername($username);

            $this->emailClient->send(
                $user['email'],
                $user['name'] ?: $user['username'],
                t('Password Reset for Hiject'),
                $this->template->render('password_reset/email', ['token' => $token])
            );
        }
    }

    /**
     * Check feature availability.
     */
    private function checkActivation()
    {
        if ($this->configModel->get('password_reset', 0) == 0) {
            throw AccessForbiddenException::getInstance()->withoutLayout();
        }
    }
}
