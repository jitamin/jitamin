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
use Hiject\User\DatabaseUserProvider;

/**
 * Rember Me Cookie Authentication Provider
 */
class RememberMeAuth extends Base implements PreAuthenticationProviderInterface
{
    /**
     * User properties
     *
     * @access protected
     * @var array
     */
    protected $userInfo = array();

    /**
     * Get authentication provider name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'RememberMe';
    }

    /**
     * Authenticate the user
     *
     * @access public
     * @return boolean
     */
    public function authenticate()
    {
        $credentials = $this->rememberMeCookie->read();

        if ($credentials !== false) {
            $session = $this->rememberMeSessionModel->find($credentials['token'], $credentials['sequence']);

            if (! empty($session)) {
                $this->rememberMeCookie->write(
                    $session['token'],
                    $this->rememberMeSessionModel->updateSequence($session['token']),
                    $session['expiration']
                );

                $this->userInfo = $this->userModel->getById($session['user_id']);

                return true;
            }
        }

        return false;
    }

    /**
     * Get user object
     *
     * @access public
     * @return DatabaseUserProvider
     */
    public function getUser()
    {
        if (empty($this->userInfo)) {
            return null;
        }

        return new DatabaseUserProvider($this->userInfo);
    }
}
