<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Auth;

use Jitamin\Core\Base;
use Jitamin\Core\Security\PreAuthenticationProviderInterface;
use Jitamin\Core\Security\SessionCheckProviderInterface;
use Jitamin\Services\Identity\ReverseProxyUserProvider;

/**
 * Reverse-Proxy Authentication Provider.
 */
class ReverseProxyAuth extends Base implements PreAuthenticationProviderInterface, SessionCheckProviderInterface
{
    /**
     * User properties.
     *
     * @var \Jitamin\Services\User\ReverseProxyUserProvider
     */
    protected $userInfo = null;

    /**
     * Get authentication provider name.
     *
     * @return string
     */
    public function getName()
    {
        return 'ReverseProxy';
    }

    /**
     * Authenticate the user.
     *
     * @return bool
     */
    public function authenticate()
    {
        $username = $this->request->getRemoteUser();

        if (!empty($username)) {
            $userProfile = $this->userModel->getByUsername($username);
            $this->userInfo = new ReverseProxyUserProvider($username, $userProfile ?: []);

            return true;
        }

        return false;
    }

    /**
     * Check if the user session is valid.
     *
     * @return bool
     */
    public function isValidSession()
    {
        return $this->request->getRemoteUser() === $this->userSession->getUsername();
    }

    /**
     * Get user object.
     *
     * @return ReverseProxyUserProvider
     */
    public function getUser()
    {
        return $this->userInfo;
    }
}
