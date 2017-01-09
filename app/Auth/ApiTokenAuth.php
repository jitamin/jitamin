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

use Jitamin\Foundation\Base;
use Jitamin\Foundation\Security\PasswordAuthenticationProviderInterface;
use Jitamin\Model\UserModel;
use Jitamin\Services\Identity\DatabaseUserProvider;

/**
 * API Token Authentication Provider.
 */
class ApiTokenAuth extends Base implements PasswordAuthenticationProviderInterface
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
        return 'API Access Token';
    }

    /**
     * Authenticate the user.
     *
     * @return bool
     */
    public function authenticate()
    {
        if (!isset($this->sessionStorage->scope) || $this->sessionStorage->scope !== 'API') {
            $this->logger->debug(__METHOD__.': Authentication provider skipped because invalid scope');

            return false;
        }

        $user = $this->db
            ->table(UserModel::TABLE)
            ->columns('id', 'password')
            ->eq('username', $this->username)
            ->eq('api_token', $this->password)
            ->notNull('api_token')
            ->eq('is_active', 1)
            ->findOne();

        if (!empty($user)) {
            $this->userInfo = $user;

            return true;
        }

        return false;
    }

    /**
     * Get user object.
     *
     * @return \Kanboard\User\DatabaseUserProvider
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
