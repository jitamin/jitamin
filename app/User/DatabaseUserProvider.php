<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\User;

use Hiject\Core\User\UserProviderInterface;

/**
 * Database User Provider.
 */
class DatabaseUserProvider implements UserProviderInterface
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
        return $this->user['id'];
    }

    /**
     * Get external id column name.
     *
     * @return string
     */
    public function getExternalIdColumn()
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
        return '';
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
        return '';
    }

    /**
     * Get user email.
     *
     * @return string
     */
    public function getEmail()
    {
        return '';
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
