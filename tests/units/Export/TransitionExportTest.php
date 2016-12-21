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

use Hiject\Export\TransitionExport;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TransitionModel;

class TransitionExportTest extends Base
{
    public function testExport()
    {
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $transitionModel = new TransitionModel($this->container);
        $transitionExportModel = new TransitionExport($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test']));

        $task_event = [
            'project_id'    => 1,
            'task_id'       => 1,
            'src_column_id' => 1,
            'dst_column_id' => 2,
            'date_moved'    => time() - 3600,
        ];

        $this->assertTrue($transitionModel->save(1, $task_event));

        $export = $transitionExportModel->export(1, date('Y-m-d'), date('Y-m-d'));
        $this->assertCount(2, $export);

        $this->assertEquals(
            ['Id', 'Task Title', 'Source column', 'Destination column', 'Executer', 'Date', 'Time spent'],
            $export[0]
        );

        $this->assertEquals(
            [1, 'test', 'Backlog', 'Ready', 'admin', date('m/d/Y H:i', time()), 1.0],
            $export[1]
        );
    }
}
