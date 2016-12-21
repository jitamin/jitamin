<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../Base.php';

use Jitamin\Core\Security\AccessMap;

class AccessMapTest extends Base
{
    public function testRoleHierarchy()
    {
        $acl = new AccessMap();
        $acl->setRoleHierarchy('admin', ['manager', 'user']);
        $acl->setRoleHierarchy('manager', ['user']);

        $this->assertEquals(['admin'], $acl->getRoleHierarchy('admin'));
        $this->assertEquals(['manager', 'admin'], $acl->getRoleHierarchy('manager'));
        $this->assertEquals(['user', 'admin', 'manager'], $acl->getRoleHierarchy('user'));
    }

    public function testGetHighestRole()
    {
        $acl = new AccessMap();
        $acl->setRoleHierarchy('manager', ['member', 'viewer']);
        $acl->setRoleHierarchy('member', ['viewer']);

        $this->assertEquals('manager', $acl->getHighestRole(['viewer', 'manager', 'member']));
        $this->assertEquals('manager', $acl->getHighestRole(['viewer', 'manager']));
        $this->assertEquals('manager', $acl->getHighestRole(['manager', 'member']));
        $this->assertEquals('member', $acl->getHighestRole(['viewer', 'member']));
        $this->assertEquals('member', $acl->getHighestRole(['member']));
        $this->assertEquals('viewer', $acl->getHighestRole(['viewer']));
    }

    public function testAddRulesAndGetRoles()
    {
        $acl = new AccessMap();
        $acl->setDefaultRole('role3');
        $acl->setRoleHierarchy('role2', ['role1']);

        $acl->add('MyController', 'myAction1', 'role2');
        $acl->add('MyController', 'myAction2', 'role1');
        $acl->add('MyAdminController', '*', 'role2');
        $acl->add('SomethingElse', ['actionA', 'actionB'], 'role2');

        $this->assertEquals(['role2'], $acl->getRoles('mycontroller', 'MyAction1'));
        $this->assertEquals(['role1', 'role2'], $acl->getRoles('mycontroller', 'MyAction2'));
        $this->assertEquals(['role2'], $acl->getRoles('Myadmincontroller', 'MyAction'));
        $this->assertEquals(['role3'], $acl->getRoles('AnotherController', 'ActionNotFound'));
        $this->assertEquals(['role2'], $acl->getRoles('somethingelse', 'actiona'));
        $this->assertEquals(['role2'], $acl->getRoles('somethingelse', 'actionb'));
        $this->assertEquals(['role3'], $acl->getRoles('somethingelse', 'actionc'));
    }
}
