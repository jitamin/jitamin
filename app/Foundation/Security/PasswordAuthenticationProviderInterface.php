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
 * Password Authentication Provider Interface.
 */
interface PasswordAuthenticationProviderInterface extends AuthenticationProviderInterface
{
    /**
     * Get user object.
     *
     * @return \Jitamin\Foundation\User\UserProviderInterface
     */
    public function getUser();

    /**
     * Set username.
     *
     * @param string $username
     */
    public function setUsername($username);

    /**
     * Set password.
     *
     * @param string $password
     */
    public function setPassword($password);
}
