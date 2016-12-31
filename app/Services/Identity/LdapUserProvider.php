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
use Jitamin\Model\LanguageModel;

/**
 * LDAP User Provider.
 */
class LdapUserProvider implements UserProviderInterface
{
    /**
     * LDAP DN.
     *
     * @var string
     */
    protected $dn;

    /**
     * LDAP username.
     *
     * @var string
     */
    protected $username;

    /**
     * User name.
     *
     * @var string
     */
    protected $name;

    /**
     * Email.
     *
     * @var string
     */
    protected $email;

    /**
     * User role.
     *
     * @var string
     */
    protected $role;

    /**
     * Group LDAP DNs.
     *
     * @var string[]
     */
    protected $groupIds;

    /**
     * User photo.
     *
     * @var string
     */
    protected $photo = '';

    /**
     * User language.
     *
     * @var string
     */
    protected $language = '';

    /**
     * Constructor.
     *
     * @param string   $dn
     * @param string   $username
     * @param string   $name
     * @param string   $email
     * @param string   $role
     * @param string[] $groupIds
     * @param string   $photo
     * @param string   $language
     */
    public function __construct($dn, $username, $name, $email, $role, array $groupIds, $photo = '', $language = '')
    {
        $this->dn = $dn;
        $this->username = $username;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->groupIds = $groupIds;
        $this->photo = $photo;
        $this->language = $language;
    }

    /**
     * Return true to allow automatic user creation.
     *
     * @return bool
     */
    public function isUserCreationAllowed()
    {
        return LDAP_USER_CREATION;
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
     * Get external id column name.
     *
     * @return string
     */
    public function getExternalIdColumn()
    {
        return 'username';
    }

    /**
     * Get external id.
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->getUsername();
    }

    /**
     * Get user role.
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return LDAP_USERNAME_CASE_SENSITIVE ? $this->username : strtolower($this->username);
    }

    /**
     * Get full name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get user email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get groups DN.
     *
     * @return string[]
     */
    public function getExternalGroupIds()
    {
        return $this->groupIds;
    }

    /**
     * Get extra user attributes.
     *
     * @return array
     */
    public function getExtraAttributes()
    {
        $attributes = ['is_ldap_user' => 1];

        if (!empty($this->language)) {
            $attributes['language'] = LanguageModel::findCode($this->language);
        }

        return $attributes;
    }

    /**
     * Get User DN.
     *
     * @return string
     */
    public function getDn()
    {
        return $this->dn;
    }

    /**
     * Get user photo.
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}
