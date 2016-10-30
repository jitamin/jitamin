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
 * Authentication Provider Interface
 */
interface AuthenticationProviderInterface
{
    /**
     * Get authentication provider name
     *
     * @access public
     * @return string
     */
    public function getName();

    /**
     * Authenticate the user
     *
     * @access public
     * @return boolean
     */
    public function authenticate();
}
