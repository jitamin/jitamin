<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../Base.php';

use Jitamin\Model\ProjectDailyStatsModel;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\TaskModel;
use Jitamin\Model\TaskStatusModel;

class ProjectDailyStatsTest extends Base
{
    public function testUpdateTotals()
    {
        $p = new ProjectModel($this->container);
        $pds = new ProjectDailyStatsModel($this->container);
        $tc = new TaskModel($this->container);
        $ts = new TaskStatusModel($this->container);

        $this->assertEquals(1, $p->create(['name' => 'UnitTest']));
        $this->assertEquals(2, $p->create(['name' => 'UnitTest']));

        $this->assertEquals(1, $tc->create(['title' => 'Task #1', 'project_id' => 1, 'date_started' => strtotime('-1 day')]));
        $this->assertEquals(2, $tc->create(['title' => 'Task #2', 'project_id' => 1]));
        $this->assertEquals(3, $tc->create(['title' => 'Task #3', 'project_id' => 2]));

        $pds->updateTotals(1, date('Y-m-d', strtotime('-1 day')));

        $this->assertTrue($ts->close(1));
        $pds->updateTotals(1, date('Y-m-d'));

        $metrics = $pds->getRawMetrics(1, date('Y-m-d', strtotime('-1days')), date('Y-m-d'));
        $expected = [
            [
                'day'            => date('Y-m-d', strtotime('-1days')),
                'avg_lead_time'  => 0,
                'avg_cycle_time' => 43200,
            ],
            [
                'day'            => date('Y-m-d'),
                'avg_lead_time'  => 0,
                'avg_cycle_time' => 43200,
            ],
        ];

        $this->assertEquals($expected[0]['day'], $metrics[0]['day']);
        $this->assertEquals($expected[1]['day'], $metrics[1]['day']);

        $this->assertEquals($expected[0]['avg_lead_time'], $metrics[0]['avg_lead_time'], '', 2);
        $this->assertEquals($expected[1]['avg_lead_time'], $metrics[1]['avg_lead_time'], '', 2);

        $this->assertEquals($expected[0]['avg_cycle_time'], $metrics[0]['avg_cycle_time'], '', 2);
        $this->assertEquals($expected[1]['avg_cycle_time'], $metrics[1]['avg_cycle_time'], '', 2);
    }
}
