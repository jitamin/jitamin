<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Helper;

use Jitamin\Foundation\Base;

/**
 * User helpers.
 */
class UserHelper extends Base
{
    /**
     * Return true if the logged user as unread notifications.
     *
     * @return bool
     */
    public function hasNotifications()
    {
        return $this->userUnreadNotificationModel->hasNotifications($this->userSession->getId());
    }

    /**
     * Get initials from a user.
     *
     * @param string $name
     *
     * @return string
     */
    public function getInitials($name)
    {
        $initials = '';

        foreach (explode(' ', $name, 2) as $string) {
            $initials .= mb_substr($string, 0, 1, 'UTF-8');
        }

        return mb_strtoupper($initials, 'UTF-8');
    }

    /**
     * Return the user full name.
     *
     * @param array $user User properties
     *
     * @return string
     */
    public function getFullname(array $user = [])
    {
        $user = empty($user) ? $this->userSession->getAll() : $user;

        return $user['name'] ?: $user['username'];
    }

    /**
     * Get user id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->userSession->getId();
    }

    /**
     * Check if the given user_id is the connected user.
     *
     * @param int $user_id User id
     *
     * @return bool
     */
    public function isCurrentUser($user_id)
    {
        return $this->userSession->getId() == $user_id;
    }

    /**
     * Return if the logged user is admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->userSession->isAdmin();
    }

    /**
     * Get role name.
     *
     * @param string $role
     *
     * @return string
     */
    public function getRoleName($role = '')
    {
        return $this->role->getRoleName($role ?: $this->userSession->getRole());
    }

    /**
     * Check application access.
     *
     * @param string $controller
     * @param string $action
     *
     * @return bool
     */
    public function hasAccess($controller, $action, $plugin = '')
    {
        if (!$this->userSession->isLogged()) {
            return false;
        }

        $key = 'app_access:'.$controller.$action;
        $result = $this->memoryCache->get($key);

        if ($result === null) {
            $result = $this->applicationAuthorization->isAllowed($controller, $action, $this->userSession->getRole(), $plugin);
            $this->memoryCache->set($key, $result);
        }

        return $result;
    }

    /**
     * Check project access.
     *
     * @param string $controller
     * @param string $action
     * @param int    $project_id
     *
     * @return bool
     */
    public function hasProjectAccess($controller, $action, $project_id)
    {
        $key = 'project_access:'.$controller.$action.$project_id;
        $result = $this->memoryCache->get($key);

        if ($result === null) {
            $result = $this->helper->projectRole->checkProjectAccess($controller, $action, $project_id);
            $this->memoryCache->set($key, $result);
        }

        return $result;
    }

    /**
     * Check if a user is stargazer.
     *
     * @param int $project_id
     * @param int $user_id
     *
     * @return bool
     */
    public function isStargazer($project_id, $user_id)
    {
        return $this->projectStarModel->isStargazer($project_id, $user_id);
    }
}
