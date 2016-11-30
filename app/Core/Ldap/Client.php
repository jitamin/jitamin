<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Ldap;

use LogicException;
use Psr\Log\LoggerInterface;

/**
 * LDAP Client.
 */
class Client
{
    /**
     * LDAP resource.
     *
     * @var resource
     */
    protected $ldap;

    /**
     * Logger instance.
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Establish LDAP connection.
     *
     * @static
     *
     * @param string $username
     * @param string $password
     *
     * @return Client
     */
    public static function connect($username = null, $password = null)
    {
        $client = new static();
        $client->open($client->getLdapServer());
        $username = $username ?: $client->getLdapUsername();
        $password = $password ?: $client->getLdapPassword();

        if (empty($username) && empty($password)) {
            $client->useAnonymousAuthentication();
        } else {
            $client->authenticate($username, $password);
        }

        return $client;
    }

    /**
     * Get server connection.
     *
     * @return resource
     */
    public function getConnection()
    {
        return $this->ldap;
    }

    /**
     * Establish server connection.
     *
     * @param string $server LDAP server hostname or IP
     * @param int    $port   LDAP port
     * @param bool   $tls    Start TLS
     * @param bool   $verify Skip SSL certificate verification
     *
     * @throws ClientException
     *
     * @return Client
     */
    public function open($server, $port = LDAP_PORT, $tls = LDAP_START_TLS, $verify = LDAP_SSL_VERIFY)
    {
        if (!function_exists('ldap_connect')) {
            throw new ClientException('LDAP: The PHP LDAP extension is required');
        }

        if (!$verify) {
            putenv('LDAPTLS_REQCERT=never');
        }

        $this->ldap = ldap_connect($server, $port);

        if ($this->ldap === false) {
            throw new ClientException('LDAP: Unable to connect to the LDAP server');
        }

        ldap_set_option($this->ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldap, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($this->ldap, LDAP_OPT_NETWORK_TIMEOUT, 1);
        ldap_set_option($this->ldap, LDAP_OPT_TIMELIMIT, 1);

        if ($tls && !@ldap_start_tls($this->ldap)) {
            throw new ClientException('LDAP: Unable to start TLS');
        }

        return $this;
    }

    /**
     * Anonymous authentication.
     *
     * @throws ClientException
     *
     * @return bool
     */
    public function useAnonymousAuthentication()
    {
        if (!@ldap_bind($this->ldap)) {
            throw new ClientException('Unable to perform anonymous binding');
        }

        return true;
    }

    /**
     * Authentication with username/password.
     *
     * @param string $bind_rdn
     * @param string $bind_password
     *
     * @throws ClientException
     *
     * @return bool
     */
    public function authenticate($bind_rdn, $bind_password)
    {
        if (!@ldap_bind($this->ldap, $bind_rdn, $bind_password)) {
            throw new ClientException('LDAP authentication failure for "'.$bind_rdn.'"');
        }

        return true;
    }

    /**
     * Get LDAP server name.
     *
     * @return string
     */
    public function getLdapServer()
    {
        if (!LDAP_SERVER) {
            throw new LogicException('LDAP server not configured, check the parameter LDAP_SERVER');
        }

        return LDAP_SERVER;
    }

    /**
     * Get LDAP username (proxy auth).
     *
     * @return string
     */
    public function getLdapUsername()
    {
        return LDAP_USERNAME;
    }

    /**
     * Get LDAP password (proxy auth).
     *
     * @return string
     */
    public function getLdapPassword()
    {
        return LDAP_PASSWORD;
    }

    /**
     * Set logger.
     *
     * @param LoggerInterface $logger
     *
     * @return Client
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Get logger.
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Test if a logger is defined.
     *
     * @return bool
     */
    public function hasLogger()
    {
        return $this->logger !== null;
    }
}
