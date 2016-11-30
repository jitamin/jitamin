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

use Hiject\Core\Ldap\Entries;

class EntriesTest extends Base
{
    private $entries = [
        'count' => 2,
        0       => [
            'cn' => [
                'count' => 1,
                0       => 'Hiject Other Group',
            ],
            0       => 'cn',
            'count' => 1,
            'dn'    => 'CN=Hiject Other Group,CN=Users,DC=hiject,DC=local',
        ],
        1 => [
            'cn' => [
                'count' => 1,
                0       => 'Hiject Users',
            ],
            0       => 'cn',
            'count' => 1,
            'dn'    => 'CN=Hiject Users,CN=Users,DC=hiject,DC=local',
        ],
    ];

    public function testGetAll()
    {
        $entries = new Entries([]);
        $this->assertEmpty($entries->getAll());

        $entries = new Entries($this->entries);
        $result = $entries->getAll();
        $this->assertCount(2, $result);
        $this->assertInstanceOf('Hiject\Core\Ldap\Entry', $result[0]);
        $this->assertEquals('CN=Hiject Users,CN=Users,DC=hiject,DC=local', $result[1]->getDn());
        $this->assertEquals('Hiject Users', $result[1]->getFirstValue('cn'));
    }

    public function testGetFirst()
    {
        $entries = new Entries([]);
        $this->assertEquals('', $entries->getFirstEntry()->getDn());

        $entries = new Entries($this->entries);
        $result = $entries->getFirstEntry();
        $this->assertInstanceOf('Hiject\Core\Ldap\Entry', $result);
        $this->assertEquals('CN=Hiject Other Group,CN=Users,DC=hiject,DC=local', $result->getDn());
        $this->assertEquals('Hiject Other Group', $result->getFirstValue('cn'));
    }
}
