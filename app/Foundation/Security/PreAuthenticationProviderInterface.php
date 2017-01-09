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
 * Pre-Authentication Provider Interface.
 */
interface PreAuthenticationProviderInterface extends AuthenticationProviderInterface
{
    /**
     * Get user object.
     *
     * @return \Jitamin\Foundation\User\UserProviderInterface
     */
    public function getUser();
}
