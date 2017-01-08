<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Api;

use Jitamin\Core\Ldap\Client as LdapClient;
use Jitamin\Core\Ldap\ClientException as LdapException;
use Jitamin\Core\Ldap\User as LdapUser;
use Jitamin\Core\Security\Role;
use LogicException;

/**
 * User API controller.
 */
class UserController extends Controller
{
    /**
     * Get a specific user by id.
     *
     * @param int $user_id User id
     *
     * @return array
     */
    public function getUser($user_id)
    {
        return $this->userModel->getById($user_id);
    }

    /**
     * Get a specific user by the username.
     *
     * @param string $username Username
     *
     * @return array
     */
    public function getUserByName($username)
    {
        return $this->userModel->getByUsername($username);
    }

    /**
     * Get all users.
     *
     * @return array
     */
    public function getAllUsers()
    {
        return $this->userModel->getAll();
    }

    /**
     * Remove a specific user.
     *
     * @param int $user_id User id
     *
     * @return bool
     */
    public function removeUser($user_id)
    {
        return $this->userModel->remove($user_id);
    }

    /**
     * Disable a specific user.
     *
     * @param int $user_id
     *
     * @return bool
     */
    public function disableUser($user_id)
    {
        return $this->userModel->disable($user_id);
    }

    /**
     * Enable a specific user.
     *
     * @param int $user_id
     *
     * @return bool
     */
    public function enableUser($user_id)
    {
        return $this->userModel->enable($user_id);
    }

    /**
     * Return true if the user is active.
     *
     * @param int $user_id User id
     *
     * @return bool
     */
    public function isActiveUser($user_id)
    {
        return $this->userModel->isActive($user_id);
    }

    /**
     * Add a new user in the database.
     *
     * @param string $username
     * @param string $password
     * @param string $name
     * @param string $email
     * @param string $role
     *
     * @return bool|int
     */
    public function createUser($username, $password, $name = '', $email = '', $role = Role::APP_USER)
    {
        $values = [
            'username'     => $username,
            'password'     => $password,
            'confirmation' => $password,
            'name'         => $name,
            'email'        => $email,
            'role'         => $role,
        ];

        list($valid) = $this->userValidator->validateCreation($values);

        return $valid ? $this->userModel->create($values) : false;
    }

    /**
     * Create LDAP user in the database.
     *
     * Only "anonymous" and "proxy" LDAP authentication are supported by this method
     *
     * User information will be fetched from the LDAP server
     *
     * @param string $username
     *
     * @return bool|int
     */
    public function createLdapUser($username)
    {
        if (LDAP_BIND_TYPE === 'user') {
            $this->logger->error('LDAP authentication "user" is not supported by this API call');

            return false;
        }

        try {
            $ldap = LdapClient::connect();
            $ldap->setLogger($this->logger);
            $user = LdapUser::getUser($ldap, $username);

            if ($user === null) {
                $this->logger->info('User not found in LDAP server');

                return false;
            }

            if ($user->getUsername() === '') {
                throw new LogicException('Username not found in LDAP profile, check the parameter LDAP_USER_ATTRIBUTE_USERNAME');
            }

            $values = [
                'username'     => $user->getUsername(),
                'name'         => $user->getName(),
                'email'        => $user->getEmail(),
                'role'         => $user->getRole(),
                'is_ldap_user' => 1,
            ];

            return $this->userModel->create($values);
        } catch (LdapException $e) {
            $this->logger->error($e->getMessage());

            return false;
        }
    }

    /**
     * Update a user in the database.
     *
     * @param int    $id
     * @param string $username
     * @param string $password
     * @param string $name
     * @param string $email
     * @param string $role
     *
     * @return bool
     */
    public function updateUser($id, $username = null, $name = null, $email = null, $role = null)
    {
        $values = $this->filterValues([
            'id'       => $id,
            'username' => $username,
            'name'     => $name,
            'email'    => $email,
            'role'     => $role,
        ]);

        list($valid) = $this->userValidator->validateApiModification($values);

        return $valid && $this->userModel->update($values);
    }
}
