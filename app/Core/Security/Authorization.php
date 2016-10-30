<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Security;

/**
 * Authorization Handler
 */
class Authorization
{
    /**
     * Access Map
     *
     * @access private
     * @var AccessMap
     */
    private $accessMap;

    /**
     * Constructor
     *
     * @access public
     * @param  AccessMap  $accessMap
     */
    public function __construct(AccessMap $accessMap)
    {
        $this->accessMap = $accessMap;
    }

    /**
     * Check if the given role is allowed to access to the specified resource
     *
     * @access public
     * @param  string  $controller
     * @param  string  $method
     * @param  string  $role
     * @return boolean
     */
    public function isAllowed($controller, $method, $role)
    {
        $roles = $this->accessMap->getRoles($controller, $method);
        return in_array($role, $roles);
    }
}
