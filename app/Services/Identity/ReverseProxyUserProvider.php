<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Services\Identity;

use Hiject\Core\Security\Role;
use Hiject\Core\Identity\UserProviderInterface;

/**
 * Reverse Proxy User Provider.
 */
class ReverseProxyUserProvider implements UserProviderInterface
{
    /**
     * Username.
     *
     * @var string
     */
    protected $username = '';

    /**
     * User profile if the user already exists.
     *
     * @var array
     */
    private $userProfile = [];

    /**
     * Constructor.
     *
     * @param string $username
     */
    public function __construct($username, array $userProfile = [])
    {
        $this->username = $username;
        $this->userProfile = $userProfile;
    }

    /**
     * Return true to allow automatic user creation.
     *
     * @return bool
     */
    public function isUserCreationAllowed()
    {
        return true;
    }

    /**
     * Get internal id.
     *
     * @return string
     */
    public function getInternalId()
    {
        return '';
    }

    /**
     * Get external id column name.
     *
     * @return string
     */
    public function getExternalIdColumn()
    {
        return 'username';
    }

    /**
     * Get external id.
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->username;
    }

    /**
     * Get user role.
     *
     * @return string
     */
    public function getRole()
    {
        if (REVERSE_PROXY_DEFAULT_ADMIN === $this->username) {
            return Role::APP_ADMIN;
        }

        if (isset($this->userProfile['role'])) {
            return $this->userProfile['role'];
        }

        return Role::APP_USER;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get full name.
     *
     * @return string
     */
    public function getName()
    {
        return '';
    }

    /**
     * Get user email.
     *
     * @return string
     */
    public function getEmail()
    {
        return REVERSE_PROXY_DEFAULT_DOMAIN !== '' ? $this->username.'@'.REVERSE_PROXY_DEFAULT_DOMAIN : '';
    }

    /**
     * Get external group ids.
     *
     * @return array
     */
    public function getExternalGroupIds()
    {
        return [];
    }

    /**
     * Get extra user attributes.
     *
     * @return array
     */
    public function getExtraAttributes()
    {
        return [
            'is_ldap_user'       => 1,
            'disable_login_form' => 1,
        ];
    }
}
