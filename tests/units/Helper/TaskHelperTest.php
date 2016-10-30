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

use Hiject\Helper\TaskHelper;

class TaskHelperTest extends Base
{
    public function testSelectPriority()
    {
        $helper = new TaskHelper($this->container);
        $this->assertNotEmpty($helper->selectPriority(array('priority_end' => '1', 'priority_start' => '5', 'priority_default' => '2'), array()));
        $this->assertNotEmpty($helper->selectPriority(array('priority_end' => '3', 'priority_start' => '1', 'priority_default' => '2'), array()));
        $this->assertEmpty($helper->selectPriority(array('priority_end' => '3', 'priority_start' => '3', 'priority_default' => '2'), array()));
    }

    public function testFormatPriority()
    {
        $helper = new TaskHelper($this->container);

        $this->assertEquals(
            '<span class="task-board-priority" title="Task priority">P2</span>',
            $helper->formatPriority(array('priority_end' => '3', 'priority_start' => '1', 'priority_default' => '2'), array('priority' => 2))
        );

        $this->assertEquals(
            '<span class="task-board-priority" title="Task priority">P-6</span>',
            $helper->formatPriority(array('priority_end' => '3', 'priority_start' => '1', 'priority_default' => '2'), array('priority' => -6))
        );

        $this->assertEmpty($helper->formatPriority(array('priority_end' => '3', 'priority_start' => '3', 'priority_default' => '2'), array()));
    }
}
