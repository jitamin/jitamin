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
 * Password Authentication Provider Interface
 */
interface PasswordAuthenticationProviderInterface extends AuthenticationProviderInterface
{
    /**
     * Get user object
     *
     * @access public
     * @return \Hiject\Core\User\UserProviderInterface
     */
    public function getUser();

    /**
     * Set username
     *
     * @access public
     * @param  string $username
     */
    public function setUsername($username);

    /**
     * Set password
     *
     * @access public
     * @param  string $password
     */
    public function setPassword($password);
}
