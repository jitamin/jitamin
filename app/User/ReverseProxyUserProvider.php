<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\User;

use Hiject\Core\User\UserProviderInterface;
use Hiject\Core\Security\Role;

/**
 * Reverse Proxy User Provider
 */
class ReverseProxyUserProvider implements UserProviderInterface
{
    /**
     * Username
     *
     * @access protected
     * @var string
     */
    protected $username = '';

    /**
     * User profile if the user already exists
     *
     * @access protected
     * @var array
     */
    private $userProfile = [];

    /**
     * Constructor
     *
     * @access public
     * @param  string $username
     */
    public function __construct($username, array $userProfile = [])
    {
        $this->username = $username;
        $this->userProfile = $userProfile;
    }

    /**
     * Return true to allow automatic user creation
     *
     * @access public
     * @return boolean
     */
    public function isUserCreationAllowed()
    {
        return true;
    }

    /**
     * Get internal id
     *
     * @access public
     * @return string
     */
    public function getInternalId()
    {
        return '';
    }

    /**
     * Get external id column name
     *
     * @access public
     * @return string
     */
    public function getExternalIdColumn()
    {
        return 'username';
    }

    /**
     * Get external id
     *
     * @access public
     * @return string
     */
    public function getExternalId()
    {
        return $this->username;
    }

    /**
     * Get user role
     *
     * @access public
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
     * Get username
     *
     * @access public
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get full name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return '';
    }

    /**
     * Get user email
     *
     * @access public
     * @return string
     */
    public function getEmail()
    {
        return REVERSE_PROXY_DEFAULT_DOMAIN !== '' ? $this->username.'@'.REVERSE_PROXY_DEFAULT_DOMAIN : '';
    }

    /**
     * Get external group ids
     *
     * @access public
     * @return array
     */
    public function getExternalGroupIds()
    {
        return [];
    }

    /**
     * Get extra user attributes
     *
     * @access public
     * @return array
     */
    public function getExtraAttributes()
    {
        return [
            'is_ldap_user' => 1,
            'disable_login_form' => 1,
        ];
    }
}
