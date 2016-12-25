<?php

namespace Jitamin\Auth;

use Jitamin\Core\Base;
use Jitamin\Core\Security\PasswordAuthenticationProviderInterface;
use Jitamin\Model\UserModel;
use Jitamin\Services\Identity\DatabaseUserProvider;

/**
 * API Token Authentication Provider
 */
class ApiTokenAuth extends Base implements PasswordAuthenticationProviderInterface
{
    /**
     * User properties
     *
     * @var array
     */
    protected $userInfo = [];

    /**
     * Username
     *
     * @access protected
     * @var string
     */
    protected $username = '';

    /**
     * Password
     *
     * @access protected
     * @var string
     */
    protected $password = '';

    /**
     * Get authentication provider name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'API Access Token';
    }

    /**
     * Authenticate the user
     *
     * @access public
     * @return boolean
     */
    public function authenticate()
    {
        if (! isset($this->sessionStorage->scope) ||  $this->sessionStorage->scope !== 'API') {
            $this->logger->debug(__METHOD__.': Authentication provider skipped because invalid scope');
            return false;
        }

        $user = $this->db
            ->table(UserModel::TABLE)
            ->columns('id', 'password')
            ->eq('username', $this->username)
            ->eq('api_token', $this->password)
            ->notNull('api_token')
            ->eq('is_active', 1)
            ->findOne();

        if (! empty($user)) {
            $this->userInfo = $user;
            return true;
        }

        return false;
    }

    /**
     * Get user object
     *
     * @access public
     * @return \Kanboard\User\DatabaseUserProvider
     */
    public function getUser()
    {
        if (empty($this->userInfo)) {
            return null;
        }

        return new DatabaseUserProvider($this->userInfo);
    }

    /**
     * Set username
     *
     * @access public
     * @param  string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Set password
     *
     * @access public
     * @param  string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}