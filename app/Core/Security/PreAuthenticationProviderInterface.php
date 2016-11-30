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
 * Pre-Authentication Provider Interface.
 */
interface PreAuthenticationProviderInterface extends AuthenticationProviderInterface
{
    /**
     * Get user object.
     *
     * @return \Hiject\Core\User\UserProviderInterface
     */
    public function getUser();
}
