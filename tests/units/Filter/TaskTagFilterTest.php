<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Filter\TaskTagFilter;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskTagModel;

require_once __DIR__.'/../Base.php';

class TaskTagFilterTest extends Base
{
    public function testWithMultipleMatches()
    {
        $taskFinderModel = new TaskFinderModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskTagModel = new TaskTagModel($this->container);
        $query = $taskFinderModel->getExtendedQuery();

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test1']));
        $this->assertEquals(2, $taskModel->create(['project_id' => 1, 'title' => 'test2']));
        $this->assertEquals(3, $taskModel->create(['project_id' => 1, 'title' => 'test3']));

        $this->assertTrue($taskTagModel->save(1, 1, ['My tag 1', 'My tag 2', 'My tag 3']));
        $this->assertTrue($taskTagModel->save(1, 2, ['My tag 3']));

        $filter = new TaskTagFilter();
        $filter->setDatabase($this->container['db']);
        $filter->withQuery($query);
        $filter->withValue('my tag 3');
        $filter->apply();

        $tasks = $query->asc(TaskModel::TABLE.'.title')->findAll();
        $this->assertCount(2, $tasks);
        $this->assertEquals('test1', $tasks[0]['title']);
        $this->assertEquals('test2', $tasks[1]['title']);
    }

    public function testWithSingleMatch()
    {
        $taskFinderModel = new TaskFinderModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskTagModel = new TaskTagModel($this->container);
        $query = $taskFinderModel->getExtendedQuery();

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test1']));
        $this->assertEquals(2, $taskModel->create(['project_id' => 1, 'title' => 'test2']));
        $this->assertEquals(3, $taskModel->create(['project_id' => 1, 'title' => 'test3']));

        $this->assertTrue($taskTagModel->save(1, 1, ['My tag 1', 'My tag 2', 'My tag 3']));
        $this->assertTrue($taskTagModel->save(1, 2, ['My tag 3']));

        $filter = new TaskTagFilter();
        $filter->setDatabase($this->container['db']);
        $filter->withQuery($query);
        $filter->withValue('my tag 2');
        $filter->apply();

        $tasks = $query->findAll();
        $this->assertCount(1, $tasks);
        $this->assertEquals('test1', $tasks[0]['title']);
    }

    public function testWithNoMatch()
    {
        $taskFinderModel = new TaskFinderModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskTagModel = new TaskTagModel($this->container);
        $query = $taskFinderModel->getExtendedQuery();

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test1']));
        $this->assertEquals(2, $taskModel->create(['project_id' => 1, 'title' => 'test2']));
        $this->assertEquals(3, $taskModel->create(['project_id' => 1, 'title' => 'test3']));

        $this->assertTrue($taskTagModel->save(1, 1, ['My tag 1', 'My tag 2', 'My tag 3']));
        $this->assertTrue($taskTagModel->save(1, 2, ['My tag 3']));

        $filter = new TaskTagFilter();
        $filter->setDatabase($this->container['db']);
        $filter->withQuery($query);
        $filter->withValue('my tag 42');
        $filter->apply();

        $tasks = $query->findAll();
        $this->assertCount(0, $tasks);
    }

    public function testWithSameTagInMultipleProjects()
    {
        $taskFinderModel = new TaskFinderModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskTagModel = new TaskTagModel($this->container);
        $query = $taskFinderModel->getExtendedQuery();

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(2, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test1']));
        $this->assertEquals(2, $taskModel->create(['project_id' => 2, 'title' => 'test2']));

        $this->assertTrue($taskTagModel->save(1, 1, ['My tag']));
        $this->assertTrue($taskTagModel->save(2, 2, ['My tag']));

        $filter = new TaskTagFilter();
        $filter->setDatabase($this->container['db']);
        $filter->withQuery($query);
        $filter->withValue('my tag');
        $filter->apply();

        $tasks = $query->asc(TaskModel::TABLE.'.title')->findAll();
        $this->assertCount(2, $tasks);
        $this->assertEquals('test1', $tasks[0]['title']);
        $this->assertEquals('test2', $tasks[1]['title']);
    }

    public function testWithNone()
    {
        $taskFinderModel = new TaskFinderModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskTagModel = new TaskTagModel($this->container);
        $query = $taskFinderModel->getExtendedQuery();

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test1']));
        $this->assertEquals(2, $taskModel->create(['project_id' => 1, 'title' => 'test2']));
        $this->assertEquals(3, $taskModel->create(['project_id' => 1, 'title' => 'test3']));

        $this->assertTrue($taskTagModel->save(1, 1, ['My tag 1', 'My tag 2', 'My tag 3']));
        $this->assertTrue($taskTagModel->save(1, 2, ['My tag 3']));

        $filter = new TaskTagFilter();
        $filter->setDatabase($this->container['db']);
        $filter->withQuery($query);
        $filter->withValue('none');
        $filter->apply();

        $tasks = $query->findAll();
        $this->assertCount(1, $tasks);
        $this->assertEquals('test3', $tasks[0]['title']);
    }
}
