<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Foundation\Security;

/**
 * Role Definitions.
 */
class Role
{
    const APP_ADMIN = 'app-admin';
    const APP_MANAGER = 'app-manager';
    const APP_USER = 'app-user';
    const APP_PUBLIC = 'app-public';

    const PROJECT_MANAGER = 'project-manager';
    const PROJECT_MEMBER = 'project-member';
    const PROJECT_VIEWER = 'project-viewer';

    /**
     * Get application roles.
     *
     * @return array
     */
    public function getApplicationRoles()
    {
        return [
            self::APP_ADMIN   => t('Administrator'),
            self::APP_MANAGER => t('Manager'),
            self::APP_USER    => t('User'),
        ];
    }

    /**
     * Get project roles.
     *
     * @return array
     */
    public function getProjectRoles()
    {
        return [
            self::PROJECT_MANAGER => t('Project Manager'),
            self::PROJECT_MEMBER  => t('Project Member'),
            self::PROJECT_VIEWER  => t('Project Viewer'),
        ];
    }

    /**
     * Check if the given role is custom or not.
     *
     * @param string $role
     *
     * @return bool
     */
    public function isCustomProjectRole($role)
    {
        return !empty($role) && $role !== self::PROJECT_MANAGER && $role !== self::PROJECT_MEMBER && $role !== self::PROJECT_VIEWER;
    }

    /**
     * Get role name.
     *
     * @param string $role
     *
     * @return string
     */
    public function getRoleName($role)
    {
        $roles = $this->getApplicationRoles() + $this->getProjectRoles();

        return isset($roles[$role]) ? $roles[$role] : t('Unknown');
    }
}
