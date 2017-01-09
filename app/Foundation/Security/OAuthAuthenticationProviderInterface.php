<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Security;

/**
 * OAuth2 Authentication Provider Interface.
 */
interface OAuthAuthenticationProviderInterface extends AuthenticationProviderInterface
{
    /**
     * Get user object.
     *
     * @return \Jitamin\Core\User\UserProviderInterface
     */
    public function getUser();

    /**
     * Unlink user.
     *
     * @param int $userId
     *
     * @return bool
     */
    public function unlink($userId);

    /**
     * Get configured OAuth2 service.
     *
     * @return \Jitamin\Core\Http\OAuth2
     */
    public function getService();

    /**
     * Set OAuth2 code.
     *
     * @param string $code
     *
     * @return OAuthAuthenticationProviderInterface
     */
    public function setCode($code);
}
