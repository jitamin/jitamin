<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../Base.php';

use Hiject\Model\ProjectModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskMetadataModel;

class TaskMetadataTest extends Base
{
    public function testOperations()
    {
        $p = new ProjectModel($this->container);
        $tm = new TaskMetadataModel($this->container);
        $tc = new TaskModel($this->container);

        $this->assertEquals(1, $p->create(['name' => 'project #1']));
        $this->assertEquals(1, $tc->create(['title' => 'task #1', 'project_id' => 1]));
        $this->assertEquals(2, $tc->create(['title' => 'task #2', 'project_id' => 1]));

        $this->assertTrue($tm->save(1, ['key1' => 'value1']));
        $this->assertTrue($tm->save(1, ['key1' => 'value2']));
        $this->assertTrue($tm->save(2, ['key1' => 'value1']));
        $this->assertTrue($tm->save(2, ['key2' => 'value2']));

        $this->assertEquals('value2', $tm->get(1, 'key1'));
        $this->assertEquals('value1', $tm->get(2, 'key1'));
        $this->assertEquals('', $tm->get(2, 'key3'));
        $this->assertEquals('default', $tm->get(2, 'key3', 'default'));

        $this->assertTrue($tm->exists(2, 'key1'));
        $this->assertFalse($tm->exists(2, 'key3'));

        $this->assertEquals(['key1' => 'value2'], $tm->getAll(1));
        $this->assertEquals(['key1' => 'value1', 'key2' => 'value2'], $tm->getAll(2));

        $this->assertTrue($tm->remove(2, 'key1'));
        $this->assertFalse($tm->remove(2, 'key1'));

        $this->assertEquals(['key2' => 'value2'], $tm->getAll(2));
    }
}
