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

use Jitamin\Analytic\TaskDistributionAnalytic;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\TaskModel;

class TaskDistributionAnalyticTest extends Base
{
    public function testBuild()
    {
        $projectModel = new ProjectModel($this->container);
        $taskDistributionModel = new TaskDistributionAnalytic($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'test1']));

        $this->createTasks(1, 20, 1);
        $this->createTasks(2, 30, 1);
        $this->createTasks(3, 40, 1);
        $this->createTasks(4, 10, 1);

        $expected = [
            [
                'column_title' => 'Backlog',
                'nb_tasks'     => 20,
                'percentage'   => 20.0,
            ],
            [
                'column_title' => 'Ready',
                'nb_tasks'     => 30,
                'percentage'   => 30.0,
            ],
            [
                'column_title' => 'Work in progress',
                'nb_tasks'     => 40,
                'percentage'   => 40.0,
            ],
            [
                'column_title' => 'Done',
                'nb_tasks'     => 10,
                'percentage'   => 10.0,
            ],
        ];

        $this->assertEquals($expected, $taskDistributionModel->build(1));
    }

    private function createTasks($column_id, $nb_active, $nb_inactive)
    {
        $taskModel = new TaskModel($this->container);

        for ($i = 0; $i < $nb_active; $i++) {
            $this->assertNotFalse($taskModel->create(['project_id' => 1, 'title' => 'test', 'column_id' => $column_id, 'is_active' => 1]));
        }

        for ($i = 0; $i < $nb_inactive; $i++) {
            $this->assertNotFalse($taskModel->create(['project_id' => 1, 'title' => 'test', 'column_id' => $column_id, 'is_active' => 0]));
        }
    }
}
