<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Services\Identity;

use Jitamin\Core\Identity\UserProviderInterface;

/**
 * OAuth User Provider.
 */
abstract class OAuthUserProvider implements UserProviderInterface
{
    /**
     * User properties.
     *
     * @var array
     */
    protected $user = [];

    /**
     * Constructor.
     *
     * @param array $user
     */
    public function __construct(array $user)
    {
        $this->user = $user;
    }

    /**
     * Return true to allow automatic user creation.
     *
     * @return bool
     */
    public function isUserCreationAllowed()
    {
        return false;
    }

    /**
     * Get internal id.
     *
     * @return string
     */
    public function getInternalId()
    {
        return '';
    }

    /**
     * Get external id.
     *
     * @return string
     */
    public function getExternalId()
    {
        return isset($this->user['id']) ? $this->user['id'] : '';
    }

    /**
     * Get user role.
     *
     * @return string
     */
    public function getRole()
    {
        return '';
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return '';
    }

    /**
     * Get full name.
     *
     * @return string
     */
    public function getName()
    {
        return isset($this->user['name']) ? $this->user['name'] : '';
    }

    /**
     * Get user email.
     *
     * @return string
     */
    public function getEmail()
    {
        return isset($this->user['email']) ? $this->user['email'] : '';
    }

    /**
     * Get external group ids.
     *
     * @return array
     */
    public function getExternalGroupIds()
    {
        return [];
    }

    /**
     * Get extra user attributes.
     *
     * @return array
     */
    public function getExtraAttributes()
    {
        return [];
    }
}
