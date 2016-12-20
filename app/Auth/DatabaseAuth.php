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
use Hiject\Services\Identity\DatabaseUserProvider;

/**
 * Database Authentication Provider.
 */
class DatabaseAuth extends Base implements PasswordAuthenticationProviderInterface, SessionCheckProviderInterface
{
    /**
     * User properties.
     *
     * @var array
     */
    protected $userInfo = [];

    /**
     * Username.
     *
     * @var string
     */
    protected $username = '';

    /**
     * Password.
     *
     * @var string
     */
    protected $password = '';

    /**
     * Get authentication provider name.
     *
     * @return string
     */
    public function getName()
    {
        return 'Database';
    }

    /**
     * Authenticate the user.
     *
     * @return bool
     */
    public function authenticate()
    {
        $user = $this->db
            ->table(UserModel::TABLE)
            ->columns('id', 'password')
            ->eq(strpos($this->username, '@') === false ? 'username' : 'email', $this->username)
            ->eq('disable_login_form', 0)
            ->eq('is_ldap_user', 0)
            ->eq('is_active', 1)
            ->findOne();

        if (!empty($user) && password_verify($this->password, $user['password'])) {
            $this->userInfo = $user;

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
        return $this->userModel->isActive($this->userSession->getId());
    }

    /**
     * Get user object.
     *
     * @return \Hiject\Services\User\DatabaseUserProvider
     */
    public function getUser()
    {
        if (empty($this->userInfo)) {
            return;
        }

        return new DatabaseUserProvider($this->userInfo);
    }

    /**
     * Set username.
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Set password.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}
