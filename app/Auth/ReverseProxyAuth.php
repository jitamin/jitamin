<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Auth;

use Hiject\Core\Base;
use Hiject\Core\Security\PreAuthenticationProviderInterface;
use Hiject\Core\Security\SessionCheckProviderInterface;
use Hiject\User\ReverseProxyUserProvider;

/**
 * Reverse-Proxy Authentication Provider
 */
class ReverseProxyAuth extends Base implements PreAuthenticationProviderInterface, SessionCheckProviderInterface
{
    /**
     * User properties
     *
     * @access protected
     * @var \Hiject\User\ReverseProxyUserProvider
     */
    protected $userInfo = null;

    /**
     * Get authentication provider name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'ReverseProxy';
    }

    /**
     * Authenticate the user
     *
     * @access public
     * @return boolean
     */
    public function authenticate()
    {
        $username = $this->request->getRemoteUser();

        if (! empty($username)) {
            $userProfile = $this->userModel->getByUsername($username);
            $this->userInfo = new ReverseProxyUserProvider($username, $userProfile ?: array());
            return true;
        }

        return false;
    }

    /**
     * Check if the user session is valid
     *
     * @access public
     * @return boolean
     */
    public function isValidSession()
    {
        return $this->request->getRemoteUser() === $this->userSession->getUsername();
    }

    /**
     * Get user object
     *
     * @access public
     * @return ReverseProxyUserProvider
     */
    public function getUser()
    {
        return $this->userInfo;
    }
}
