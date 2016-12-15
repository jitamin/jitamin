<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../Base.php';

use Hiject\Core\Security\Role;
use Hiject\Core\User\UserSync;
use Hiject\User\LdapUserProvider;

class UserSyncTest extends Base
{
    public function testSynchronizeNewUser()
    {
        $user = new LdapUserProvider('ldapId', 'bob', 'Bob', 'bob@bob', Role::APP_MANAGER, []);
        $userSync = new UserSync($this->container);

        $profile = [
            'id'           => 2,
            'username'     => 'bob',
            'name'         => 'Bob',
            'email'        => 'bob@bob',
            'role'         => Role::APP_MANAGER,
            'is_ldap_user' => 1,
        ];

        $this->assertArraySubset($profile, $userSync->synchronize($user));
    }

    public function testSynchronizeExistingUser()
    {
        $userSync = new UserSync($this->container);
        $user = new LdapUserProvider('ldapId', 'admin', 'Admin', 'email@localhost', Role::APP_MANAGER, []);

        $profile = [
            'id'       => 1,
            'username' => 'admin',
            'name'     => 'Admin',
            'email'    => 'email@localhost',
            'role'     => Role::APP_MANAGER,
        ];

        $this->assertArraySubset($profile, $userSync->synchronize($user));

        $user = new LdapUserProvider('ldapId', 'admin', '', '', Role::APP_ADMIN, []);

        $profile = [
            'id'       => 1,
            'username' => 'admin',
            'name'     => 'Admin',
            'email'    => 'email@localhost',
            'role'     => Role::APP_ADMIN,
        ];

        $this->assertArraySubset($profile, $userSync->synchronize($user));
    }
}
