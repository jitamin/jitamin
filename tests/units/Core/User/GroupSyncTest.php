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

use Hiject\Core\User\GroupSync;
use Hiject\Model\GroupModel;
use Hiject\Model\GroupMemberModel;

class GroupSyncTest extends Base
{
    public function testAddGroups()
    {
        $group = new GroupModel($this->container);
        $groupMember = new GroupMemberModel($this->container);
        $groupSync = new GroupSync($this->container);

        $this->assertEquals(1, $group->create('My Group 1', 'externalId1'));
        $this->assertEquals(2, $group->create('My Group 2', 'externalId2'));

        $this->assertTrue($groupMember->addUser(1, 1));

        $this->assertTrue($groupMember->isMember(1, 1));
        $this->assertFalse($groupMember->isMember(2, 1));

        $groupSync->synchronize(1, ['externalId1', 'externalId2', 'externalId3']);

        $this->assertTrue($groupMember->isMember(1, 1));
        $this->assertTrue($groupMember->isMember(2, 1));
    }

    public function testRemoveGroups()
    {
        $group = new GroupModel($this->container);
        $groupMember = new GroupMemberModel($this->container);
        $groupSync = new GroupSync($this->container);

        $this->assertEquals(1, $group->create('My Group 1', 'externalId1'));
        $this->assertEquals(2, $group->create('My Group 2', 'externalId2'));

        $this->assertTrue($groupMember->addUser(1, 1));

        $this->assertTrue($groupMember->isMember(1, 1));
        $this->assertFalse($groupMember->isMember(2, 1));

        $groupSync->synchronize(1, ['externalId3']);

        $this->assertFalse($groupMember->isMember(1, 1));
        $this->assertFalse($groupMember->isMember(2, 1));
    }

    public function testBoth()
    {
        $group = new GroupModel($this->container);
        $groupMember = new GroupMemberModel($this->container);
        $groupSync = new GroupSync($this->container);

        $this->assertEquals(1, $group->create('My Group 1', 'externalId1'));
        $this->assertEquals(2, $group->create('My Group 2', 'externalId2'));
        $this->assertEquals(3, $group->create('My Group 3', 'externalId3'));

        $this->assertTrue($groupMember->addUser(1, 1));
        $this->assertTrue($groupMember->addUser(2, 1));

        $this->assertTrue($groupMember->isMember(1, 1));
        $this->assertTrue($groupMember->isMember(2, 1));
        $this->assertFalse($groupMember->isMember(3, 1));

        $groupSync->synchronize(1, ['externalId1', 'externalId3']);

        $this->assertTrue($groupMember->isMember(1, 1));
        $this->assertFalse($groupMember->isMember(2, 1));
        $this->assertTrue($groupMember->isMember(3, 1));
    }

    public function testThatInternalGroupsAreNotTouched()
    {
        $group = new GroupModel($this->container);
        $groupMember = new GroupMemberModel($this->container);
        $groupSync = new GroupSync($this->container);

        $this->assertEquals(1, $group->create('My Group 1'));
        $this->assertTrue($groupMember->addUser(1, 1));
        $this->assertTrue($groupMember->isMember(1, 1));

        $groupSync->synchronize(1, ['externalId3']);

        $this->assertTrue($groupMember->isMember(1, 1));
    }
}
