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

use Jitamin\Core\Ldap\Entry;

class EntryTest extends Base
{
    private $entry = [
        'count'       => 2,
        'dn'          => 'uid=my_user,ou=People,dc=jitamin,dc=local',
        'displayname' => [
            'count' => 1,
            0       => 'My LDAP user',
        ],
        'broken' => [],
        'mail'   => [
            'count' => 2,
            0       => 'user1@localhost',
            1       => 'user2@localhost',
        ],
        'samaccountname' => [
            'count' => 1,
            0       => 'my_ldap_user',
        ],
        0 => 'displayname',
        1 => 'mail',
        2 => 'samaccountname',
    ];

    public function testGetAll()
    {
        $expected = [
            'user1@localhost',
            'user2@localhost',
        ];

        $entry = new Entry($this->entry);
        $this->assertEquals($expected, $entry->getAll('mail'));
        $this->assertEmpty($entry->getAll('not found'));
        $this->assertEmpty($entry->getAll('broken'));
    }

    public function testGetFirst()
    {
        $entry = new Entry($this->entry);
        $this->assertEquals('user1@localhost', $entry->getFirstValue('mail'));
        $this->assertEquals('', $entry->getFirstValue('not found'));
        $this->assertEquals('default', $entry->getFirstValue('not found', 'default'));
        $this->assertEquals('default', $entry->getFirstValue('broken', 'default'));
    }

    public function testGetDn()
    {
        $entry = new Entry($this->entry);
        $this->assertEquals('uid=my_user,ou=People,dc=jitamin,dc=local', $entry->getDn());

        $entry = new Entry([]);
        $this->assertEquals('', $entry->getDn());
    }

    public function testHasValue()
    {
        $entry = new Entry($this->entry);
        $this->assertTrue($entry->hasValue('mail', 'user2@localhost'));
        $this->assertFalse($entry->hasValue('mail', 'user3@localhost'));
        $this->assertTrue($entry->hasValue('displayname', 'My LDAP user'));
        $this->assertFalse($entry->hasValue('displayname', 'Something else'));
    }
}
