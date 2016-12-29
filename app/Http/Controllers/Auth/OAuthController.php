<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Auth;

use Jitamin\Controller\BaseController;
use Jitamin\Core\Security\OAuthAuthenticationProviderInterface;

/**
 * OAuth Controller.
 */
class OAuthController extends BaseController
{
    /**
     * Redirect to the provider if no code received.
     *
     * @param string $provider
     */
    protected function step1($provider)
    {
        $code = $this->request->getStringParam('code');
        $state = $this->request->getStringParam('state');

        if (!empty($code)) {
            $this->step2($provider, $code, $state);
        } else {
            $this->response->redirect($this->authenticationManager->getProvider($provider)->getService()->getAuthorizationUrl());
        }
    }

    /**
     * Link or authenticate the user.
     *
     * @param string $providerName
     * @param string $code
     * @param string $state
     */
    protected function step2($providerName, $code, $state)
    {
        $provider = $this->authenticationManager->getProvider($providerName);
        $provider->setCode($code);
        $hasValidState = $provider->getService()->isValidateState($state);

        if ($this->userSession->isLogged()) {
            if ($hasValidState) {
                $this->link($provider);
            } else {
                $this->flash->failure(t('The OAuth2 state parameter is invalid'));
                $this->response->redirect($this->helper->url->to('Profile/ProfileController', 'external', ['user_id' => $this->userSession->getId()]));
            }
        } else {
            if ($hasValidState) {
                $this->authenticate($providerName);
            } else {
                $this->authenticationFailure(t('The OAuth2 state parameter is invalid'));
            }
        }
    }

    /**
     * Link the account.
     *
     * @param OAuthAuthenticationProviderInterface $provider
     */
    protected function link(OAuthAuthenticationProviderInterface $provider)
    {
        if (!$provider->authenticate()) {
            $this->flash->failure(t('External authentication failed'));
        } else {
            $this->userProfile->assign($this->userSession->getId(), $provider->getUser());
            $this->flash->success(t('Your external account is linked to your profile successfully.'));
        }

        $this->response->redirect($this->helper->url->to('Profile/ProfileController', 'external', ['user_id' => $this->userSession->getId()]));
    }

    /**
     * Unlink external account.
     */
    public function unlink()
    {
        $backend = $this->request->getStringParam('backend');
        $this->checkCSRFParam();

        if ($this->authenticationManager->getProvider($backend)->unlink($this->userSession->getId())) {
            $this->flash->success(t('Your external account is not linked anymore to your profile.'));
        } else {
            $this->flash->failure(t('Unable to unlink your external account.'));
        }

        $this->response->redirect($this->helper->url->to('Profile/ProfileController', 'external', ['user_id' => $this->userSession->getId()]));
    }

    /**
     * Authenticate the account.
     *
     * @param string $providerName
     */
    protected function authenticate($providerName)
    {
        if ($this->authenticationManager->oauthAuthentication($providerName)) {
            $this->response->redirect($this->helper->url->to('Dashboard/DashboardController', 'index'));
        } else {
            $this->authenticationFailure(t('External authentication failed'));
        }
    }

    /**
     * Show login failure page.
     *
     * @param string $message
     */
    protected function authenticationFailure($message)
    {
        $this->response->html($this->helper->layout->app('auth/index', [
            'errors'    => ['login' => $message],
            'values'    => [],
            'no_layout' => true,
            'title'     => t('Login'),
        ]));
    }
}
