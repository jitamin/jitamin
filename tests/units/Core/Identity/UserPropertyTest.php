<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../Base.php';

use Jitamin\Foundation\Identity\UserProperty;
use Jitamin\Foundation\Security\Role;
use Jitamin\Services\Identity\LdapUserProvider;

class UserPropertyTest extends Base
{
    public function testGetProperties()
    {
        $user = new LdapUserProvider('ldapId', 'bob', 'Bob', '', Role::APP_USER, []);

        $expected = [
            'username'     => 'bob',
            'name'         => 'Bob',
            'role'         => Role::APP_USER,
            'is_ldap_user' => 1,
        ];

        $this->assertEquals($expected, UserProperty::getProperties($user));

        $user = new LdapUserProvider('ldapId', 'bob', '', '', '', []);

        $expected = [
            'username'     => 'bob',
            'is_ldap_user' => 1,
        ];

        $this->assertEquals($expected, UserProperty::getProperties($user));
    }

    public function testFilterPropertiesDoNotOverrideExistingValue()
    {
        $profile = [
            'id'           => 123,
            'username'     => 'bob',
            'name'         => null,
            'email'        => '',
            'other_column' => 'myvalue',
            'role'         => Role::APP_ADMIN,
        ];

        $properties = [
            'external_id' => '456',
            'username'    => 'bobby',
            'name'        => 'Bobby',
            'email'       => 'admin@localhost',
            'role'        => '',
        ];

        $expected = [
            'name'  => 'Bobby',
            'email' => 'admin@localhost',
        ];

        $this->assertEquals($expected, UserProperty::filterProperties($profile, $properties));

        $profile = [
            'id'           => 123,
            'username'     => 'bob',
            'name'         => null,
            'email'        => '',
            'other_column' => 'myvalue',
            'role'         => Role::APP_ADMIN,
        ];

        $properties = [
            'external_id' => '456',
            'username'    => 'bobby',
            'name'        => 'Bobby',
            'email'       => 'admin@localhost',
            'role'        => null,
        ];

        $expected = [
            'name'  => 'Bobby',
            'email' => 'admin@localhost',
        ];

        $this->assertEquals($expected, UserProperty::filterProperties($profile, $properties));
    }

    public function testFilterPropertiesOverrideExistingValueWhenNecessary()
    {
        $profile = [
            'id'           => 123,
            'username'     => 'bob',
            'name'         => null,
            'email'        => '',
            'other_column' => 'myvalue',
            'role'         => Role::APP_USER,
        ];

        $properties = [
            'external_id' => '456',
            'username'    => 'bobby',
            'name'        => 'Bobby',
            'email'       => 'admin@localhost',
            'role'        => Role::APP_MANAGER,
        ];

        $expected = [
            'name'  => 'Bobby',
            'email' => 'admin@localhost',
            'role'  => Role::APP_MANAGER,
        ];

        $this->assertEquals($expected, UserProperty::filterProperties($profile, $properties));
    }

    public function testFilterPropertiesDoNotOverrideSameValue()
    {
        $profile = [
            'id'           => 123,
            'username'     => 'bob',
            'name'         => 'Bobby',
            'email'        => 'admin@example.org',
            'other_column' => 'myvalue',
            'role'         => Role::APP_MANAGER,
        ];

        $properties = [
            'external_id' => '456',
            'username'    => 'bobby',
            'name'        => 'Bobby',
            'email'       => 'admin@localhost',
            'role'        => Role::APP_MANAGER,
        ];

        $expected = [
            'email' => 'admin@localhost',
        ];

        $this->assertEquals($expected, UserProperty::filterProperties($profile, $properties));
    }
}
