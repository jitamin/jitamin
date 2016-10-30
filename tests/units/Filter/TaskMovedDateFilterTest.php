<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Filter\TaskMovedDateFilter;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\TaskModificationModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\UserModel;

require_once __DIR__.'/../Base.php';

class TaskMovedDateFilterTest extends Base
{
    public function test()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskCreationModel($this->container);
        $taskModification = new TaskModificationModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(array('name' => 'Test')));
        $this->assertEquals(1, $taskCreation->create(array('title' => 'Test1', 'project_id' => 1)));
        $this->assertTrue($taskModification->update(array('id' => 1, 'date_moved' => time())));
        $this->assertEquals(2, $taskCreation->create(array('title' => 'Test2', 'project_id' => 1)));
        $this->assertTrue($taskModification->update(array('id' => 2, 'date_moved' => strtotime('-1days'))));
        $this->assertEquals(3, $taskCreation->create(array('title' => 'Test3', 'project_id' => 1)));
        $this->assertTrue($taskModification->update(array('id' => 3, 'date_moved' => strtotime('-3days'))));

        $query = $taskFinder->getExtendedQuery();
        $filter = new TaskMovedDateFilter('>='.date('Y-m-d', strtotime('-1days')));
        $filter->setDateParser($this->container['dateParser']);
        $filter->withQuery($query)->apply();

        $this->assertCount(2, $query->findAll());

        $query = $taskFinder->getExtendedQuery();
        $filter = new TaskMovedDateFilter('<yesterday');
        $filter->setDateParser($this->container['dateParser']);
        $filter->withQuery($query)->apply();

        $this->assertCount(1, $query->findAll());
    }
}
