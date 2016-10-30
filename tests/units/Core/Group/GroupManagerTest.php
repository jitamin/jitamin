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

use Hiject\Model\GroupModel;
use Hiject\Core\Group\GroupManager;
use Hiject\Group\DatabaseBackendGroupProvider;

class GroupManagerTest extends Base
{
    public function testFind()
    {
        $groupModel = new GroupModel($this->container);
        $groupManager = new GroupManager;

        $this->assertEquals(1, $groupModel->create('Group 1'));
        $this->assertEquals(2, $groupModel->create('Group 2'));

        $this->assertEmpty($groupManager->find('group 1'));

        $groupManager->register(new DatabaseBackendGroupProvider($this->container));
        $groupManager->register(new DatabaseBackendGroupProvider($this->container));

        $groups = $groupManager->find('group 1');
        $this->assertCount(1, $groups);
        $this->assertInstanceOf('Hiject\Group\DatabaseGroupProvider', $groups[0]);
        $this->assertEquals('Group 1', $groups[0]->getName());
        $this->assertEquals('', $groups[0]->getExternalId());
        $this->assertEquals(1, $groups[0]->getInternalId());

        $groups = $groupManager->find('grou');
        $this->assertCount(2, $groups);
        $this->assertInstanceOf('Hiject\Group\DatabaseGroupProvider', $groups[0]);
        $this->assertInstanceOf('Hiject\Group\DatabaseGroupProvider', $groups[1]);
        $this->assertEquals('Group 1', $groups[0]->getName());
        $this->assertEquals('Group 2', $groups[1]->getName());
        $this->assertEquals('', $groups[0]->getExternalId());
        $this->assertEquals('', $groups[1]->getExternalId());
        $this->assertEquals(1, $groups[0]->getInternalId());
        $this->assertEquals(2, $groups[1]->getInternalId());
    }
}
