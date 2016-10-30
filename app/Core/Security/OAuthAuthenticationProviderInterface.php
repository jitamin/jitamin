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
 * OAuth2 Authentication Provider Interface
 */
interface OAuthAuthenticationProviderInterface extends AuthenticationProviderInterface
{
    /**
     * Get user object
     *
     * @access public
     * @return \Hiject\Core\User\UserProviderInterface
     */
    public function getUser();

    /**
     * Unlink user
     *
     * @access public
     * @param  integer $userId
     * @return bool
     */
    public function unlink($userId);

    /**
     * Get configured OAuth2 service
     *
     * @access public
     * @return \Hiject\Core\Http\OAuth2
     */
    public function getService();

    /**
     * Set OAuth2 code
     *
     * @access public
     * @param  string  $code
     * @return OAuthAuthenticationProviderInterface
     */
    public function setCode($code);
}
