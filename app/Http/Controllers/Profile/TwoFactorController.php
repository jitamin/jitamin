<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Profile;

use Jitamin\Core\Controller\AccessForbiddenException;

/**
 * Two Factor Auth controller.
 */
class TwoFactorController extends ProfileController
{
    /**
     * Show form to disable/enable 2FA.
     */
    public function index()
    {
        $user = $this->getUser();
        $this->checkCurrentUser($user);
        unset($this->sessionStorage->twoFactorSecret);

        $this->response->html($this->helper->layout->profile('profile/twofactor/index', [
            'user'     => $user,
            'provider' => $this->authenticationManager->getPostAuthenticationProvider()->getName(),
        ]));
    }

    /**
     * Show page with secret and test form.
     */
    public function show()
    {
        $user = $this->getUser();
        $this->checkCurrentUser($user);

        $label = $user['email'] ?: $user['username'];
        $provider = $this->authenticationManager->getPostAuthenticationProvider();

        if (!isset($this->sessionStorage->twoFactorSecret)) {
            $provider->generateSecret();
            $provider->beforeCode();
            $this->sessionStorage->twoFactorSecret = $provider->getSecret();
        } else {
            $provider->setSecret($this->sessionStorage->twoFactorSecret);
        }

        $this->response->html($this->helper->layout->profile('profile/twofactor/show', [
            'user'       => $user,
            'secret'     => $this->sessionStorage->twoFactorSecret,
            'qrcode_url' => $provider->getQrCodeUrl($label),
            'key_url'    => $provider->getKeyUrl($label),
        ]));
    }

    /**
     * Test code and save secret.
     */
    public function test()
    {
        $user = $this->getUser();
        $this->checkCurrentUser($user);

        $values = $this->request->getValues();

        $provider = $this->authenticationManager->getPostAuthenticationProvider();
        $provider->setCode(empty($values['code']) ? '' : $values['code']);
        $provider->setSecret($this->sessionStorage->twoFactorSecret);

        if ($provider->authenticate()) {
            $this->flash->success(t('The two factor authentication code is valid.'));

            $this->userModel->update([
                'id'                  => $user['id'],
                'twofactor_activated' => 1,
                'twofactor_secret'    => $this->authenticationManager->getPostAuthenticationProvider()->getSecret(),
            ]);

            unset($this->sessionStorage->twoFactorSecret);
            $this->userSession->disablePostAuthentication();

            $this->response->redirect($this->helper->url->to('Profile/TwoFactorController', 'index', ['user_id' => $user['id']]));
        } else {
            $this->flash->failure(t('The two factor authentication code is not valid.'));
            $this->response->redirect($this->helper->url->to('Profile/TwoFactorController', 'show', ['user_id' => $user['id']]));
        }
    }

    /**
     * Disable 2FA for the current user.
     */
    public function deactivate()
    {
        $user = $this->getUser();
        $this->checkCurrentUser($user);

        $this->userModel->update([
            'id'                  => $user['id'],
            'twofactor_activated' => 0,
            'twofactor_secret'    => '',
        ]);

        // Allow the user to test or disable the feature
        $this->userSession->disablePostAuthentication();

        $this->flash->success(t('User updated successfully.'));
        $this->response->redirect($this->helper->url->to('Profile/TwoFactorController', 'index', ['user_id' => $user['id']]));
    }

    /**
     * Check 2FA.
     */
    public function check()
    {
        $user = $this->getUser();
        $this->checkCurrentUser($user);

        $values = $this->request->getValues();

        $provider = $this->authenticationManager->getPostAuthenticationProvider();
        $provider->setCode(empty($values['code']) ? '' : $values['code']);
        $provider->setSecret($user['twofactor_secret']);

        if ($provider->authenticate()) {
            $this->userSession->validatePostAuthentication();
            $this->flash->success(t('The two factor authentication code is valid.'));
            $this->response->redirect($this->helper->url->to('Dashboard/DashboardController', 'index'));
        } else {
            $this->flash->failure(t('The two factor authentication code is not valid.'));
            $this->response->redirect($this->helper->url->to('Profile/TwoFactorController', 'code'));
        }
    }

    /**
     * Ask the 2FA code.
     */
    public function code()
    {
        if (!isset($this->sessionStorage->twoFactorBeforeCodeCalled)) {
            $provider = $this->authenticationManager->getPostAuthenticationProvider();
            $provider->beforeCode();
            $this->sessionStorage->twoFactorBeforeCodeCalled = true;
        }

        $this->response->html($this->helper->layout->app('profile/twofactor/check', [
            'title' => t('Check two factor authentication code'),
        ]));
    }

    /**
     * Disable 2FA for a user.
     */
    public function disable()
    {
        $user = $this->getUser();

        if ($this->request->getStringParam('disable') === 'yes') {

            $this->userModel->update([
                'id'                  => $user['id'],
                'twofactor_activated' => 0,
                'twofactor_secret'    => '',
            ]);

            return $this->response->redirect($this->helper->url->to('Profile/ProfileController', 'show', ['user_id' => $user['id']]));
        }

        return $this->response->html($this->helper->layout->profile('profile/twofactor/disable', [
            'user' => $user,
        ]));
    }

    /**
     * Only the current user can access to 2FA settings.
     *
     * @param array $user
     *
     * @throws AccessForbiddenException
     */
    protected function checkCurrentUser(array $user)
    {
        if ($user['id'] != $this->userSession->getId()) {
            throw new AccessForbiddenException();
        }
    }
}
