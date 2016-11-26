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

use Hiject\Model\TaskCreationModel;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskModel;
use Hiject\Analytic\AverageLeadCycleTimeAnalytic;

class AverageLeadCycleTimeAnalyticTest extends Base
{
    public function testBuild()
    {
        $taskCreationModel = new TaskCreationModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $averageLeadCycleTimeAnalytic = new AverageLeadCycleTimeAnalytic($this->container);
        $now = time();

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'test1']));

        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'test']));
        $this->assertEquals(2, $taskCreationModel->create(['project_id' => 1, 'title' => 'test']));
        $this->assertEquals(3, $taskCreationModel->create(['project_id' => 1, 'title' => 'test']));
        $this->assertEquals(4, $taskCreationModel->create(['project_id' => 1, 'title' => 'test']));
        $this->assertEquals(5, $taskCreationModel->create(['project_id' => 1, 'title' => 'test']));

        // LT=3600 CT=1800
        $this->container['db']->table(TaskModel::TABLE)->eq('id', 1)->update(['date_completed' => $now + 3600, 'date_started' => $now + 1800]);

        // LT=1800 CT=900
        $this->container['db']->table(TaskModel::TABLE)->eq('id', 2)->update(['date_completed' => $now + 1800, 'date_started' => $now + 900]);

        // LT=3600 CT=0
        $this->container['db']->table(TaskModel::TABLE)->eq('id', 3)->update(['date_completed' => $now + 3600]);

        // LT=2*3600 CT=0
        $this->container['db']->table(TaskModel::TABLE)->eq('id', 4)->update(['date_completed' => $now + 2 * 3600]);

        // CT=0
        $this->container['db']->table(TaskModel::TABLE)->eq('id', 5)->update(['date_started' => $now + 900]);

        $stats = $averageLeadCycleTimeAnalytic->build(1);

        $this->assertEquals(5, $stats['count']);
        $this->assertEquals(3600 + 1800 + 3600 + 2*3600, $stats['total_lead_time'], '', 5);
        $this->assertEquals(1800 + 900, $stats['total_cycle_time'], '', 5);
        $this->assertEquals((3600 + 1800 + 3600 + 2*3600) / 5, $stats['avg_lead_time'], '', 5);
        $this->assertEquals((1800 + 900) / 5, $stats['avg_cycle_time'], '', 5);
    }

    public function testBuildWithNoTasks()
    {
        $projectModel = new ProjectModel($this->container);
        $averageLeadCycleTimeAnalytic = new AverageLeadCycleTimeAnalytic($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'test1']));

        $stats = $averageLeadCycleTimeAnalytic->build(1);

        $expected = [
            'count' => 0,
            'total_lead_time' => 0,
            'total_cycle_time' => 0,
            'avg_lead_time' => 0,
            'avg_cycle_time' => 0,
        ];

        $this->assertEquals($expected, $stats);
    }
}
