<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Identity;

use Hiject\Core\Base;
use Hiject\Core\Security\Role;

/**
 * User Session.
 */
class UserSession extends Base
{
    /**
     * Refresh current session if necessary.
     *
     * @param int $user_id
     */
    public function refresh($user_id)
    {
        if ($this->getId() == $user_id) {
            $this->initialize($this->userModel->getById($user_id));
        }
    }

    /**
     * Update user session.
     *
     * @param array $user
     */
    public function initialize(array $user)
    {
        foreach (['password', 'is_admin', 'is_project_admin', 'twofactor_secret'] as $column) {
            if (isset($user[$column])) {
                unset($user[$column]);
            }
        }

        $user['id'] = (int) $user['id'];
        $user['is_ldap_user'] = isset($user['is_ldap_user']) ? (bool) $user['is_ldap_user'] : false;
        $user['twofactor_activated'] = isset($user['twofactor_activated']) ? (bool) $user['twofactor_activated'] : false;

        $this->sessionStorage->user = $user;
        $this->sessionStorage->postAuthenticationValidated = false;
    }

    /**
     * Get user properties.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->sessionStorage->user;
    }

    /**
     * Get user application role.
     *
     * @return string
     */
    public function getRole()
    {
        return $this->sessionStorage->user['role'];
    }

    /**
     * Return true if the user has validated the 2FA key.
     *
     * @return bool
     */
    public function isPostAuthenticationValidated()
    {
        return isset($this->sessionStorage->postAuthenticationValidated) && $this->sessionStorage->postAuthenticationValidated === true;
    }

    /**
     * Validate 2FA for the current session.
     */
    public function validatePostAuthentication()
    {
        $this->sessionStorage->postAuthenticationValidated = true;
    }

    /**
     * Return true if the user has 2FA enabled.
     *
     * @return bool
     */
    public function hasPostAuthentication()
    {
        return isset($this->sessionStorage->user['twofactor_activated']) && $this->sessionStorage->user['twofactor_activated'] === true;
    }

    /**
     * Disable 2FA for the current session.
     */
    public function disablePostAuthentication()
    {
        $this->sessionStorage->user['twofactor_activated'] = false;
    }

    /**
     * Return true if the logged user is admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return isset($this->sessionStorage->user['role']) && $this->sessionStorage->user['role'] === Role::APP_ADMIN;
    }

    /**
     * Get the connected user id.
     *
     * @return int
     */
    public function getId()
    {
        return isset($this->sessionStorage->user['id']) ? (int) $this->sessionStorage->user['id'] : 0;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return isset($this->sessionStorage->user['username']) ? $this->sessionStorage->user['username'] : '';
    }

    /**
     * Check is the user is connected.
     *
     * @return bool
     */
    public function isLogged()
    {
        return isset($this->sessionStorage->user) && !empty($this->sessionStorage->user);
    }

    /**
     * Get project filters from the session.
     *
     * @param int $project_id
     *
     * @return string
     */
    public function getFilters($project_id)
    {
        return !empty($this->sessionStorage->filters[$project_id]) ? $this->sessionStorage->filters[$project_id] : 'status:open';
    }

    /**
     * Save project filters in the session.
     *
     * @param int    $project_id
     * @param string $filters
     */
    public function setFilters($project_id, $filters)
    {
        $this->sessionStorage->filters[$project_id] = $filters;
    }
}
