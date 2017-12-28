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
 * Access Map Definition.
 */
class AccessMap
{
    /**
     * Default role.
     *
     * @var string
     */
    private $defaultRole = '';

    /**
     * Role hierarchy.
     *
     * @var array
     */
    private $hierarchy = [];

    /**
     * Access map.
     *
     * @var array
     */
    private $map = [];

    /**
     * Define the default role when nothing match.
     *
     * @param string $role
     *
     * @return AccessMap
     */
    public function setDefaultRole($role)
    {
        $this->defaultRole = $role;

        return $this;
    }

    /**
     * Define role hierarchy.
     *
     * @param string $role
     * @param array  $subroles
     *
     * @return AccessMap
     */
    public function setRoleHierarchy($role, array $subroles)
    {
        foreach ($subroles as $subrole) {
            if (isset($this->hierarchy[$subrole])) {
                $this->hierarchy[$subrole][] = $role;
            } else {
                $this->hierarchy[$subrole] = [$role];
            }
        }

        return $this;
    }

    /**
     * Get computed role hierarchy.
     *
     * @param string $role
     *
     * @return array
     */
    public function getRoleHierarchy($role)
    {
        $roles = [$role];

        if (isset($this->hierarchy[$role])) {
            $roles = array_merge($roles, $this->hierarchy[$role]);
        }

        return $roles;
    }

    /**
     * Get the highest role from a list.
     *
     * @param array $roles
     *
     * @return string
     */
    public function getHighestRole(array $roles)
    {
        $rank = [];

        foreach ($roles as $role) {
            $rank[$role] = count($this->getRoleHierarchy($role));
        }

        asort($rank);

        return key($rank);
    }

    /**
     * Add new access rules.
     *
     * @param string $controller Controller class name
     * @param mixed  $methods    List of method name or just one method
     * @param string $role       Lowest role required
     * @param string $plugin
     *
     * @return AccessMap
     */
    public function add($controller, $methods, $role, $plugin = '')
    {
        if (is_array($methods)) {
            foreach ($methods as $method) {
                $this->addRule($controller, $method, $role, $plugin);
            }
        } else {
            $this->addRule($controller, $methods, $role, $plugin);
        }

        return $this;
    }

    /**
     * Add new access rule.
     *
     * @param string $controller
     * @param string $method
     * @param string $role
     * @param string $plugin
     *
     * @return AccessMap
     */
    private function addRule($controller, $method, $role, $plugin = '')
    {
        $controller = strtolower($controller);
        $method = strtolower($method);
        $plugin = strtolower($plugin);

        if (!isset($this->map[$plugin][$controller])) {
            $this->map[$plugin][$controller] = [];
        }

        $this->map[$plugin][$controller][$method] = $role;

        return $this;
    }

    /**
     * Get roles that match the given controller/method.
     *
     * @param string $controller
     * @param string $method
     * @param string $plugin
     *
     * @return array
     */
    public function getRoles($controller, $method, $plugin = '')
    {
        $controller = strtolower($controller);
        $method = strtolower($method);
        $plugin = strtolower($plugin);

        foreach ([$method, '*'] as $key) {
            if (isset($this->map[$plugin][$controller][$key])) {
                return $this->getRoleHierarchy($this->map[$plugin][$controller][$key]);
            }
        }

        return $this->getRoleHierarchy($this->defaultRole);
    }
}
