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

use Hiject\Model\ProjectDailyColumnStatsModel;
use Hiject\Model\ProjectModel;
use Hiject\Model\SettingModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskModel;

class ProjectDailyColumnStatsTest extends Base
{
    public function testUpdateTotalsWithScoreAtNull()
    {
        $projectModel = new ProjectModel($this->container);
        $projectDailyColumnStats = new ProjectDailyColumnStatsModel($this->container);
        $taskModel = new TaskModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test']));

        $projectDailyColumnStats->updateTotals(1, '2016-01-16');

        $task = $this->container['db']->table(TaskModel::TABLE)->findOne();
        $this->assertNull($task['score']);

        $stats = $this->container['db']->table(ProjectDailyColumnStatsModel::TABLE)
            ->asc('day')
            ->asc('column_id')
            ->columns('day', 'project_id', 'column_id', 'total', 'score')
            ->findAll();

        $expected = [
            [
                'day'        => '2016-01-16',
                'project_id' => 1,
                'column_id'  => 1,
                'total'      => 1,
                'score'      => 0,
            ],
        ];

        $this->assertEquals($expected, $stats);
    }

    public function testUpdateTotals()
    {
        $projectModel = new ProjectModel($this->container);
        $projectDailyColumnStats = new ProjectDailyColumnStatsModel($this->container);
        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));

        $this->createTasks(1, 2, 1);
        $this->createTasks(1, 3, 0);

        $this->createTasks(2, 5, 1);
        $this->createTasks(2, 8, 1);
        $this->createTasks(2, 0, 0);
        $this->createTasks(2, 0, 0);

        $projectDailyColumnStats->updateTotals(1, '2016-01-16');

        $this->createTasks(1, 9, 1);
        $this->createTasks(1, 7, 0);

        $projectDailyColumnStats->updateTotals(1, '2016-01-16');

        $this->createTasks(3, 0, 1);

        $projectDailyColumnStats->updateTotals(1, '2016-01-17');

        $stats = $this->container['db']->table(ProjectDailyColumnStatsModel::TABLE)
            ->asc('day')
            ->asc('column_id')
            ->columns('day', 'project_id', 'column_id', 'total', 'score')
            ->findAll();

        $expected = [
            [
                'day'        => '2016-01-16',
                'project_id' => 1,
                'column_id'  => 1,
                'total'      => 4,
                'score'      => 11,
            ],
            [
                'day'        => '2016-01-16',
                'project_id' => 1,
                'column_id'  => 2,
                'total'      => 4,
                'score'      => 13,
            ],
            [
                'day'        => '2016-01-17',
                'project_id' => 1,
                'column_id'  => 1,
                'total'      => 4,
                'score'      => 11,
            ],
            [
                'day'        => '2016-01-17',
                'project_id' => 1,
                'column_id'  => 2,
                'total'      => 4,
                'score'      => 13,
            ],
            [
                'day'        => '2016-01-17',
                'project_id' => 1,
                'column_id'  => 3,
                'total'      => 1,
                'score'      => 0,
            ],
        ];

        $this->assertEquals($expected, $stats);
    }

    public function testUpdateTotalsWithOnlyOpenTasks()
    {
        $settingModel = new SettingModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $projectDailyColumnStats = new ProjectDailyColumnStatsModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));
        $this->assertTrue($settingModel->save(['cfd_include_closed_tasks' => 0]));
        $this->container['memoryCache']->flush();

        $this->createTasks(1, 2, 1);
        $this->createTasks(1, 3, 0);

        $this->createTasks(2, 5, 1);
        $this->createTasks(2, 8, 1);
        $this->createTasks(2, 0, 0);
        $this->createTasks(2, 0, 0);

        $projectDailyColumnStats->updateTotals(1, '2016-01-16');

        $this->createTasks(1, 9, 1);
        $this->createTasks(1, 7, 0);

        $projectDailyColumnStats->updateTotals(1, '2016-01-16');

        $this->createTasks(3, 0, 1);

        $projectDailyColumnStats->updateTotals(1, '2016-01-17');

        $stats = $this->container['db']->table(ProjectDailyColumnStatsModel::TABLE)
            ->asc('day')
            ->asc('column_id')
            ->columns('day', 'project_id', 'column_id', 'total', 'score')
            ->findAll();

        $expected = [
            [
                'day'        => '2016-01-16',
                'project_id' => 1,
                'column_id'  => 1,
                'total'      => 2,
                'score'      => 11,
            ],
            [
                'day'        => '2016-01-16',
                'project_id' => 1,
                'column_id'  => 2,
                'total'      => 2,
                'score'      => 13,
            ],
            [
                'day'        => '2016-01-17',
                'project_id' => 1,
                'column_id'  => 1,
                'total'      => 2,
                'score'      => 11,
            ],
            [
                'day'        => '2016-01-17',
                'project_id' => 1,
                'column_id'  => 2,
                'total'      => 2,
                'score'      => 13,
            ],
            [
                'day'        => '2016-01-17',
                'project_id' => 1,
                'column_id'  => 3,
                'total'      => 1,
                'score'      => 0,
            ],
        ];

        $this->assertEquals($expected, $stats);
    }

    public function testCountDays()
    {
        $projectModel = new ProjectModel($this->container);
        $projectDailyColumnStats = new ProjectDailyColumnStatsModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));

        $this->createTasks(1, 2, 1);
        $projectDailyColumnStats->updateTotals(1, '2016-01-16');
        $this->assertEquals(1, $projectDailyColumnStats->countDays(1, '2016-01-16', '2016-01-17'));

        $projectDailyColumnStats->updateTotals(1, '2016-01-17');
        $this->assertEquals(2, $projectDailyColumnStats->countDays(1, '2016-01-16', '2016-01-17'));
    }

    public function testGetAggregatedMetrics()
    {
        $projectModel = new ProjectModel($this->container);
        $projectDailyColumnStats = new ProjectDailyColumnStatsModel($this->container);
        $this->assertEquals(1, $projectModel->create(['name' => 'UnitTest']));

        $this->createTasks(1, 2, 1);
        $this->createTasks(1, 3, 0);

        $this->createTasks(2, 5, 1);
        $this->createTasks(2, 8, 1);
        $this->createTasks(2, 0, 0);
        $this->createTasks(2, 0, 0);

        $projectDailyColumnStats->updateTotals(1, '2016-01-16');

        $this->createTasks(1, 9, 1);
        $this->createTasks(1, 7, 0);

        $projectDailyColumnStats->updateTotals(1, '2016-01-16');

        $this->createTasks(3, 0, 1);

        $projectDailyColumnStats->updateTotals(1, '2016-01-17');

        $this->createTasks(2, 1, 1);
        $this->createTasks(3, 1, 1);
        $this->createTasks(3, 0, 1);

        $projectDailyColumnStats->updateTotals(1, '2016-01-18');

        $expected = [
            ['Date', 'Backlog', 'Ready', 'Work in progress', 'Done'],
            ['2016-01-16', 4, 4, 0, 0],
            ['2016-01-17', 4, 4, 1, 0],
            ['2016-01-18', 4, 5, 3, 0],
        ];

        $this->assertSame($expected, $projectDailyColumnStats->getAggregatedMetrics(1, '2016-01-16', '2016-01-18'));

        $expected = [
            ['Date', 'Backlog', 'Ready', 'Work in progress', 'Done'],
            ['2016-01-16', 11, 13, 0, 0],
            ['2016-01-17', 11, 13, 0, 0],
            ['2016-01-18', 11, 14, 1, 0],
        ];

        $this->assertSame($expected, $projectDailyColumnStats->getAggregatedMetrics(1, '2016-01-16', '2016-01-18', 'score'));
    }

    private function createTasks($column_id, $score, $is_active)
    {
        $taskModel = new TaskModel($this->container);
        $this->assertNotFalse($taskModel->create(['project_id' => 1, 'title' => 'test', 'column_id' => $column_id, 'score' => $score, 'is_active' => $is_active]));
    }
}
