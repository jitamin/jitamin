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

use Jitamin\Core\Ldap\Entries;

class EntriesTest extends Base
{
    private $entries = [
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
    ];

    public function testGetAll()
    {
        $entries = new Entries([]);
        $this->assertEmpty($entries->getAll());

        $entries = new Entries($this->entries);
        $result = $entries->getAll();
        $this->assertCount(2, $result);
        $this->assertInstanceOf('Jitamin\Core\Ldap\Entry', $result[0]);
        $this->assertEquals('CN=Jitamin Users,CN=Users,DC=jitamin,DC=local', $result[1]->getDn());
        $this->assertEquals('Jitamin Users', $result[1]->getFirstValue('cn'));
    }

    public function testGetFirst()
    {
        $entries = new Entries([]);
        $this->assertEquals('', $entries->getFirstEntry()->getDn());

        $entries = new Entries($this->entries);
        $result = $entries->getFirstEntry();
        $this->assertInstanceOf('Jitamin\Core\Ldap\Entry', $result);
        $this->assertEquals('CN=Jitamin Other Group,CN=Users,DC=jitamin,DC=local', $result->getDn());
        $this->assertEquals('Jitamin Other Group', $result->getFirstValue('cn'));
    }
}
