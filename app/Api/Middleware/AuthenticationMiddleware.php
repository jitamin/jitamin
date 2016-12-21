<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Api\Middleware;

use Jitamin\Core\Base;
use JsonRPC\Exception\AccessDeniedException;
use JsonRPC\Exception\AuthenticationFailureException;
use JsonRPC\MiddlewareInterface;

/**
 * Class AuthenticationApiMiddleware.
 */
class AuthenticationMiddleware extends Base implements MiddlewareInterface
{
    /**
     * Execute Middleware.
     *
     * @param string $username
     * @param string $password
     * @param string $procedureName
     *
     * @throws AccessDeniedException
     * @throws AuthenticationFailureException
     */
    public function execute($username, $password, $procedureName)
    {
        $this->dispatcher->dispatch('app.bootstrap');

        if ($this->isUserAuthenticated($username, $password)) {
            $this->userSession->initialize($this->userModel->getByUsername($username));
        } elseif (!$this->isAppAuthenticated($username, $password)) {
            $this->logger->error('API authentication failure for '.$username);
            throw new AuthenticationFailureException('Wrong credentials');
        }
    }

    /**
     * Check user credentials.
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    private function isUserAuthenticated($username, $password)
    {
        return $username !== 'jsonrpc' &&
        !$this->userLockingModel->isLocked($username) &&
        $this->authenticationManager->passwordAuthentication($username, $password);
    }

    /**
     * Check administrative credentials.
     *
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    private function isAppAuthenticated($username, $password)
    {
        return $username === 'jsonrpc' && $password === $this->getApiToken();
    }

    /**
     * Get API Token.
     *
     * @return string
     */
    private function getApiToken()
    {
        if (defined('API_AUTHENTICATION_TOKEN')) {
            return API_AUTHENTICATION_TOKEN;
        }

        return $this->settingModel->get('api_token');
    }
}
