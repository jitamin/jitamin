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

use Jitamin\Core\Ldap\Entries;
use Jitamin\Core\Ldap\Group;

class LdapGroupTest extends Base
{
    private $query;
    private $client;
    private $group;

    public function setUp()
    {
        parent::setUp();

        $this->client = $this
            ->getMockBuilder('\Jitamin\Core\Ldap\Client')
            ->setMethods([
                'getConnection',
            ])
            ->getMock();

        $this->query = $this
            ->getMockBuilder('\Jitamin\Core\Ldap\Query')
            ->setConstructorArgs([$this->client])
            ->setMethods([
                'execute',
                'hasResult',
                'getEntries',
            ])
            ->getMock();

        $this->group = $this
            ->getMockBuilder('\Jitamin\Core\Ldap\Group')
            ->setConstructorArgs([$this->query])
            ->setMethods([
                'getAttributeName',
                'getBasDn',
            ])
            ->getMock();
    }

    public function testGetGroups()
    {
        $entries = new Entries([
            'count' => 2,
            0       => [
                'cn' => [
                    'count' => 1,
                    0       => 'Jitamin Other Group',
                ],
                0       => 'cn',
                'count' => 1,
                'dn'    => 'CN=Jitamin Other Group,CN=Users,DC=jitamin,DC=local',
            ],
            1 => [
                'cn' => [
                    'count' => 1,
                    0       => 'Jitamin Users',
                ],
                0       => 'cn',
                'count' => 1,
                'dn'    => 'CN=Jitamin Users,CN=Users,DC=jitamin,DC=local',
            ],
        ]);

        $this->client
            ->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue('my_ldap_resource'));

        $this->query
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->equalTo('CN=Users,DC=jitamin,DC=local'),
                $this->equalTo('(&(objectClass=group)(sAMAccountName=Jitamin*))')
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
            ->will($this->returnValue('CN=Users,DC=jitamin,DC=local'));

        $groups = $this->group->find('(&(objectClass=group)(sAMAccountName=Jitamin*))');
        $this->assertCount(2, $groups);
        $this->assertInstanceOf('Jitamin\Group\LdapGroupProvider', $groups[0]);
        $this->assertInstanceOf('Jitamin\Group\LdapGroupProvider', $groups[1]);
        $this->assertEquals('Jitamin Other Group', $groups[0]->getName());
        $this->assertEquals('Jitamin Users', $groups[1]->getName());
        $this->assertEquals('CN=Jitamin Other Group,CN=Users,DC=jitamin,DC=local', $groups[0]->getExternalId());
        $this->assertEquals('CN=Jitamin Users,CN=Users,DC=jitamin,DC=local', $groups[1]->getExternalId());
    }

    public function testGetGroupsWithNoResult()
    {
        $entries = new Entries([]);

        $this->client
            ->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue('my_ldap_resource'));

        $this->query
            ->expects($this->once())
            ->method('execute')
            ->with(
                $this->equalTo('CN=Users,DC=jitamin,DC=local'),
                $this->equalTo('(&(objectClass=group)(sAMAccountName=Jitamin*))')
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
            ->will($this->returnValue('CN=Users,DC=jitamin,DC=local'));

        $groups = $this->group->find('(&(objectClass=group)(sAMAccountName=Jitamin*))');
        $this->assertCount(0, $groups);
    }

    public function testGetBaseDnNotConfigured()
    {
        $this->setExpectedException('\LogicException');

        $group = new Group($this->query);
        $group->getBasDn();
    }
}
