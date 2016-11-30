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

require_once __DIR__.'/../../Base.php';

function ldap_search($link_identifier, $base_dn, $filter, array $attributes)
{
    return QueryTest::$functions->ldap_search($link_identifier, $base_dn, $filter, $attributes);
}

function ldap_get_entries($link_identifier, $result_identifier)
{
    return QueryTest::$functions->ldap_get_entries($link_identifier, $result_identifier);
}

class QueryTest extends \Base
{
    public static $functions;
    private $client;

    public function setUp()
    {
        parent::setup();

        self::$functions = $this
            ->getMockBuilder('stdClass')
            ->setMethods([
                'ldap_search',
                'ldap_get_entries',
            ])
            ->getMock();

        $this->client = $this
            ->getMockBuilder('\Hiject\Core\Ldap\Client')
            ->setMethods([
                'getConnection',
            ])
            ->getMock();
    }

    public function tearDown()
    {
        parent::tearDown();
        self::$functions = null;
    }

    public function testExecuteQuerySuccessfully()
    {
        $entries = [
            'count' => 1,
            0       => [
                'count'       => 2,
                'dn'          => 'uid=my_user,ou=People,dc=hiject,dc=local',
                'displayname' => [
                    'count' => 1,
                    0       => 'My user',
                ],
                'mail' => [
                    'count' => 2,
                    0       => 'user1@localhost',
                    1       => 'user2@localhost',
                ],
                0 => 'displayname',
                1 => 'mail',
            ],
        ];

        $this->client
            ->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue('my_ldap_resource'));

        self::$functions
            ->expects($this->once())
            ->method('ldap_search')
            ->with(
                $this->equalTo('my_ldap_resource'),
                $this->equalTo('ou=People,dc=hiject,dc=local'),
                $this->equalTo('uid=my_user'),
                $this->equalTo(['displayname'])
            )
            ->will($this->returnValue('search_resource'));

        self::$functions
            ->expects($this->once())
            ->method('ldap_get_entries')
            ->with(
                $this->equalTo('my_ldap_resource'),
                $this->equalTo('search_resource')
            )
            ->will($this->returnValue($entries));

        $query = new Query($this->client);
        $query->execute('ou=People,dc=hiject,dc=local', 'uid=my_user', ['displayname']);
        $this->assertTrue($query->hasResult());

        $this->assertEquals('My user', $query->getEntries()->getFirstEntry()->getFirstValue('displayname'));
        $this->assertEquals('user1@localhost', $query->getEntries()->getFirstEntry()->getFirstValue('mail'));
        $this->assertEquals('', $query->getEntries()->getFirstEntry()->getFirstValue('not_found'));

        $this->assertEquals('uid=my_user,ou=People,dc=hiject,dc=local', $query->getEntries()->getFirstEntry()->getDn());
        $this->assertEquals('', $query->getEntries()->getFirstEntry()->getFirstValue('missing'));
    }

    public function testExecuteQueryNotFound()
    {
        $this->client
            ->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue('my_ldap_resource'));

        self::$functions
            ->expects($this->once())
            ->method('ldap_search')
            ->with(
                $this->equalTo('my_ldap_resource'),
                $this->equalTo('ou=People,dc=hiject,dc=local'),
                $this->equalTo('uid=my_user'),
                $this->equalTo(['displayname'])
            )
            ->will($this->returnValue('search_resource'));

        self::$functions
            ->expects($this->once())
            ->method('ldap_get_entries')
            ->with(
                $this->equalTo('my_ldap_resource'),
                $this->equalTo('search_resource')
            )
            ->will($this->returnValue([]));

        $query = new Query($this->client);
        $query->execute('ou=People,dc=hiject,dc=local', 'uid=my_user', ['displayname']);
        $this->assertFalse($query->hasResult());
    }

    public function testExecuteQueryFailed()
    {
        $this->client
            ->expects($this->once())
            ->method('getConnection')
            ->will($this->returnValue('my_ldap_resource'));

        self::$functions
            ->expects($this->once())
            ->method('ldap_search')
            ->with(
                $this->equalTo('my_ldap_resource'),
                $this->equalTo('ou=People,dc=hiject,dc=local'),
                $this->equalTo('uid=my_user'),
                $this->equalTo(['displayname'])
            )
            ->will($this->returnValue(false));

        $query = new Query($this->client);
        $query->execute('ou=People,dc=hiject,dc=local', 'uid=my_user', ['displayname']);
        $this->assertFalse($query->hasResult());
    }
}
