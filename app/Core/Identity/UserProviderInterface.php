<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Identity;

/**
 * User Provider Interface.
 */
interface UserProviderInterface
{
    /**
     * Return true to allow automatic user creation.
     *
     * @return bool
     */
    public function isUserCreationAllowed();

    /**
     * Get external id column name.
     *
     * Example: google_id, github_id, gitlab_id...
     *
     * @return string
     */
    public function getExternalIdColumn();

    /**
     * Get internal id.
     *
     * If a value is returned the user properties won't be updated in the local database
     *
     * @return int
     */
    public function getInternalId();

    /**
     * Get external id.
     *
     * @return string
     */
    public function getExternalId();

    /**
     * Get user role.
     *
     * Return an empty string to not override role stored in the database
     *
     * @return string
     */
    public function getRole();

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername();

    /**
     * Get user full name.
     *
     * @return string
     */
    public function getName();

    /**
     * Get user email.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Get external group ids.
     *
     * A synchronization is done at login time,
     * the user will be member of those groups if they exists in the database
     *
     * @return string[]
     */
    public function getExternalGroupIds();

    /**
     * Get extra user attributes.
     *
     * Example: is_ldap_user, disable_login_form, notifications_enabled...
     *
     * @return array
     */
    public function getExtraAttributes();
}
