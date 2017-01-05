<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Auth;

use Jitamin\Core\Base;
use Jitamin\Core\Ldap\Client as LdapClient;
use Jitamin\Core\Ldap\ClientException as LdapException;
use Jitamin\Core\Ldap\User as LdapUser;
use Jitamin\Core\Security\PasswordAuthenticationProviderInterface;
use LogicException;

/**
 * LDAP Authentication Provider.
 */
class LdapAuth extends Base implements PasswordAuthenticationProviderInterface
{
    /**
     * User properties.
     *
     * @var \Jitamin\Services\User\LdapUserProvider
     */
    protected $userInfo = null;

    /**
     * Username.
     *
     * @var string
     */
    protected $username = '';

    /**
     * Password.
     *
     * @var string
     */
    protected $password = '';

    /**
     * Get authentication provider name.
     *
     * @return string
     */
    public function getName()
    {
        return 'LDAP';
    }

    /**
     * Authenticate the user.
     *
     * @return bool
     */
    public function authenticate()
    {
        try {
            $client = LdapClient::connect($this->getLdapUsername(), $this->getLdapPassword());
            $client->setLogger($this->logger);

            $user = LdapUser::getUser($client, $this->username);

            if ($user === null) {
                $this->logger->info('User ('.$this->username.') not found in LDAP server');

                return false;
            }

            if ($user->getUsername() === '') {
                throw new LogicException('Username not found in LDAP profile, check the parameter LDAP_USER_ATTRIBUTE_USERNAME');
            }

            $this->logger->info('Authenticate this user: '.$user->getDn());

            if ($client->authenticate($user->getDn(), $this->password)) {
                $this->userInfo = $user;

                return true;
            }
        } catch (LdapException $e) {
            $this->logger->error($e->getMessage());
        }

        return false;
    }

    /**
     * Get user object.
     *
     * @return \Jitamin\Services\User\LdapUserProvider
     */
    public function getUser()
    {
        return $this->userInfo;
    }

    /**
     * Set username.
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Set password.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get LDAP username (proxy auth).
     *
     * @return string
     */
    public function getLdapUsername()
    {
        switch ($this->getLdapBindType()) {
            case 'proxy':
                return LDAP_USERNAME;
            case 'user':
                return sprintf(LDAP_USERNAME, $this->username);
            default:
                return;
        }
    }

    /**
     * Get LDAP password (proxy auth).
     *
     * @return string
     */
    public function getLdapPassword()
    {
        switch ($this->getLdapBindType()) {
            case 'proxy':
                return LDAP_PASSWORD;
            case 'user':
                return $this->password;
            default:
                return;
        }
    }

    /**
     * Get LDAP bind type.
     *
     * @return int
     */
    public function getLdapBindType()
    {
        if (LDAP_BIND_TYPE !== 'user' && LDAP_BIND_TYPE !== 'proxy' && LDAP_BIND_TYPE !== 'anonymous') {
            throw new LogicException('Wrong value for the parameter LDAP_BIND_TYPE');
        }

        return LDAP_BIND_TYPE;
    }
}
