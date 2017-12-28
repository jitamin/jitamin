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
 * Authorization Handler.
 */
class Authorization
{
    /**
     * Access Map.
     *
     * @var AccessMap
     */
    private $accessMap;

    /**
     * Constructor.
     *
     * @param AccessMap $accessMap
     */
    public function __construct(AccessMap $accessMap)
    {
        $this->accessMap = $accessMap;
    }

    /**
     * Check if the given role is allowed to access to the specified resource.
     *
     * @param string $controller
     * @param string $method
     * @param string $role
     * @param string $plugin
     *
     * @return bool
     */
    public function isAllowed($controller, $method, $role, $plugin = '')
    {
        $roles = $this->accessMap->getRoles($controller, $method, $plugin);

        return in_array($role, $roles);
    }
}
