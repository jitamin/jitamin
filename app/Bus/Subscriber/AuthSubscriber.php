<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Bus\Subscriber;

use Jitamin\Bus\Event\AuthFailureEvent;
use Jitamin\Bus\Event\AuthSuccessEvent;
use Jitamin\Foundation\Security\AuthenticationManager;
use Jitamin\Foundation\Session\SessionManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Authentication Subscriber.
 */
class AuthSubscriber extends BaseSubscriber implements EventSubscriberInterface
{
    /**
     * Get event listeners.
     *
     * @static
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            AuthenticationManager::EVENT_SUCCESS => 'afterLogin',
            AuthenticationManager::EVENT_FAILURE => 'onLoginFailure',
            SessionManager::EVENT_DESTROY        => 'afterLogout',
        ];
    }

    /**
     * After Login callback.
     *
     * @param AuthSuccessEvent $event
     */
    public function afterLogin(AuthSuccessEvent $event)
    {
        $this->logger->debug('Subscriber executed: '.__METHOD__);

        $userAgent = $this->request->getUserAgent();
        $ipAddress = $this->request->getIpAddress();

        $this->userLockingModel->resetFailedLogin($this->userSession->getUsername());

        $this->lastLoginModel->create(
            $event->getAuthType(),
            $this->userSession->getId(),
            $ipAddress,
            $userAgent
        );

        if ($event->getAuthType() === 'RememberMe') {
            $this->userSession->validatePostAuthentication();
        }

        if (isset($this->sessionStorage->hasRememberMe) && $this->sessionStorage->hasRememberMe) {
            $session = $this->rememberMeSessionModel->create($this->userSession->getId(), $ipAddress, $userAgent);
            $this->rememberMeCookie->write($session['token'], $session['sequence'], $session['expiration']);
        }
    }

    /**
     * Destroy RememberMe session on logout.
     */
    public function afterLogout()
    {
        $this->logger->debug('Subscriber executed: '.__METHOD__);
        $credentials = $this->rememberMeCookie->read();

        if ($credentials !== false) {
            $session = $this->rememberMeSessionModel->find($credentials['token'], $credentials['sequence']);

            if (!empty($session)) {
                $this->rememberMeSessionModel->remove($session['id']);
            }

            $this->rememberMeCookie->remove();
        }
    }

    /**
     * Increment failed login counter.
     *
     * @param AuthFailureEvent $event
     */
    public function onLoginFailure(AuthFailureEvent $event)
    {
        $this->logger->debug('Subscriber executed: '.__METHOD__);
        $username = $event->getUsername();

        if (!empty($username)) {
            $this->userLockingModel->incrementFailedLogin($username);

            if ($this->userLockingModel->getFailedLogin($username) > BRUTEFORCE_LOCKDOWN) {
                $this->userLockingModel->lock($username, BRUTEFORCE_LOCKDOWN_DURATION);
            }
        }
    }
}
