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

use Hiject\Core\Ldap\Group;
use Hiject\Core\Ldap\Entries;

class LdapGroupTest extends Base
{
    private $query;
    private $client;
    private $group;

    public function setUp()
    {
        parent::setUp();

        $this->client = $this
            ->getMockBuilder('\Hiject\Core\Ldap\Client')
            ->setMethods(array(
                'getConnection',
            ))
            ->getMock();

        $this->query = $this
            ->getMockBuilder('\Hiject\Core\Ldap\Query')
            ->setConstructorArgs(array($this->client))
            ->setMethods(array(
                'execute',
                'hasResult',
                'getEntries',
            ))
            ->getMock();

        $this->group = $this
            ->getMockBuilder('\Hiject\Core\Ldap\Group')
            ->setConstructorArgs(array($this->query))
            ->setMethods(array(
                'getAttributeName',
                'getBasDn',
            ))
            ->getMock();
    }

    public function testGetGroups()
    {
        $entries = new Entries(array(
            'count' => 2,
            0 => array(
                'cn' => array(
                    'count' => 1,
                    0 => 'Hiject Other Group',
                ),
                0 => 'cn',
                'count' => 1,
                'dn' => 'CN=Hiject Other Group,CN=Users,DC=hiject,DC=local',
            ),
            1 => array(
                'cn' => array(
                    'count' => 1,
                    0 => 'Hiject Users',
                ),
                0 => 'cn',
                'count' => 1,
                'dn' => 'CN=Hiject Users,CN=Users,DC=hiject,DC=local',
            ),
        ));

        $this->client
            ->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue('my_ldap_resource'));

        $this->query
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->equalTo('CN=Users,DC=hiject,DC=local'),
                $this->equalTo('(&(objectClass=group)(sAMAccountName=Hiject*))')
            );

        $this->query
            ->expects($this->once())
            ->method('hasResult')
            ->will($this->returnValue(true));

        $this->query
            ->expects($this->once())
            ->method('getEntries')
            ->will($this->returnValue($entries));

        $this->group
            ->expects($this->any())
            ->method('getAttributeName')
            ->will($this->returnValue('cn'));

        $this->group
            ->expects($this->any())
            ->method('getBasDn')
            ->will($this->returnValue('CN=Users,DC=hiject,DC=local'));

        $groups = $this->group->find('(&(objectClass=group)(sAMAccountName=Hiject*))');
        $this->assertCount(2, $groups);
        $this->assertInstanceOf('Hiject\Group\LdapGroupProvider', $groups[0]);
        $this->assertInstanceOf('Hiject\Group\LdapGroupProvider', $groups[1]);
        $this->assertEquals('Hiject Other Group', $groups[0]->getName());
        $this->assertEquals('Hiject Users', $groups[1]->getName());
        $this->assertEquals('CN=Hiject Other Group,CN=Users,DC=hiject,DC=local', $groups[0]->getExternalId());
        $this->assertEquals('CN=Hiject Users,CN=Users,DC=hiject,DC=local', $groups[1]->getExternalId());
    }

    public function testGetGroupsWithNoResult()
    {
        $entries = new Entries(array());

        $this->client
            ->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue('my_ldap_resource'));

        $this->query
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->equalTo('CN=Users,DC=hiject,DC=local'),
                $this->equalTo('(&(objectClass=group)(sAMAccountName=Hiject*))')
            );

        $this->query
            ->expects($this->once())
            ->method('hasResult')
            ->will($this->returnValue(false));

        $this->query
            ->expects($this->never())
            ->method('getEntries');

        $this->group
            ->expects($this->any())
            ->method('getAttributeName')
            ->will($this->returnValue('cn'));

        $this->group
            ->expects($this->any())
            ->method('getBasDn')
            ->will($this->returnValue('CN=Users,DC=hiject,DC=local'));

        $groups = $this->group->find('(&(objectClass=group)(sAMAccountName=Hiject*))');
        $this->assertCount(0, $groups);
    }

    public function testGetBaseDnNotConfigured()
    {
        $this->setExpectedException('\LogicException');

        $group = new Group($this->query);
        $group->getBasDn();
    }
}
