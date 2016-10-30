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
use Hiject\Core\Security\PasswordAuthenticationProviderInterface;
use Hiject\Core\Security\SessionCheckProviderInterface;
use Hiject\Model\UserModel;
use Hiject\User\DatabaseUserProvider;

/**
 * Database Authentication Provider
 */
class DatabaseAuth extends Base implements PasswordAuthenticationProviderInterface, SessionCheckProviderInterface
{
    /**
     * User properties
     *
     * @access protected
     * @var array
     */
    protected $userInfo = array();

    /**
     * Username
     *
     * @access protected
     * @var string
     */
    protected $username = '';

    /**
     * Password
     *
     * @access protected
     * @var string
     */
    protected $password = '';

    /**
     * Get authentication provider name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'Database';
    }

    /**
     * Authenticate the user
     *
     * @access public
     * @return boolean
     */
    public function authenticate()
    {
        $user = $this->db
            ->table(UserModel::TABLE)
            ->columns('id', 'password')
            ->eq('username', $this->username)
            ->eq('disable_login_form', 0)
            ->eq('is_ldap_user', 0)
            ->eq('is_active', 1)
            ->findOne();

        if (! empty($user) && password_verify($this->password, $user['password'])) {
            $this->userInfo = $user;
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
        return $this->userModel->isActive($this->userSession->getId());
    }

    /**
     * Get user object
     *
     * @access public
     * @return \Hiject\User\DatabaseUserProvider
     */
    public function getUser()
    {
        if (empty($this->userInfo)) {
            return null;
        }

        return new DatabaseUserProvider($this->userInfo);
    }

    /**
     * Set username
     *
     * @access public
     * @param  string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Set password
     *
     * @access public
     * @param  string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}
