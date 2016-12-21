<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Filter\TaskCreatorFilter;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\UserModel;

require_once __DIR__.'/../Base.php';

class TaskCreatorFilterTest extends Base
{
    public function testWithIntegerAssigneeId()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskCreation->create(['title' => 'Test', 'project_id' => 1, 'creator_id' => 1]));

        $filter = new TaskCreatorFilter();
        $filter->withQuery($query);
        $filter->withValue(1);
        $filter->apply();

        $this->assertCount(1, $query->findAll());

        $filter = new TaskCreatorFilter();
        $filter->withQuery($query);
        $filter->withValue(123);
        $filter->apply();

        $this->assertCount(0, $query->findAll());
    }

    public function testWithStringAssigneeId()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskCreation->create(['title' => 'Test', 'project_id' => 1, 'creator_id' => 1]));

        $filter = new TaskCreatorFilter();
        $filter->withQuery($query);
        $filter->withValue('1');
        $filter->apply();

        $this->assertCount(1, $query->findAll());

        $filter = new TaskCreatorFilter();
        $filter->withQuery($query);
        $filter->withValue('123');
        $filter->apply();

        $this->assertCount(0, $query->findAll());
    }

    public function testWithUsername()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskCreation->create(['title' => 'Test', 'project_id' => 1, 'creator_id' => 1]));

        $filter = new TaskCreatorFilter();
        $filter->withQuery($query);
        $filter->withValue('admin');
        $filter->apply();

        $this->assertCount(1, $query->findAll());

        $filter = new TaskCreatorFilter();
        $filter->withQuery($query);
        $filter->withValue('foobar');
        $filter->apply();

        $this->assertCount(0, $query->findAll());
    }

    public function testWithName()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $userModel = new UserModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(2, $userModel->create(['username' => 'foobar', 'email' => 'foobar@foobar', 'name' => 'Foo Bar']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskCreation->create(['title' => 'Test', 'project_id' => 1, 'creator_id' => 2]));

        $filter = new TaskCreatorFilter();
        $filter->withQuery($query);
        $filter->withValue('foo bar');
        $filter->apply();

        $this->assertCount(1, $query->findAll());

        $filter = new TaskCreatorFilter();
        $filter->withQuery($query);
        $filter->withValue('bob');
        $filter->apply();

        $this->assertCount(0, $query->findAll());
    }

    public function testWithNobody()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskCreation->create(['title' => 'Test', 'project_id' => 1]));

        $filter = new TaskCreatorFilter();
        $filter->withQuery($query);
        $filter->withValue('nobody');
        $filter->apply();

        $this->assertCount(1, $query->findAll());
    }

    public function testWithCurrentUser()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskCreation->create(['title' => 'Test', 'project_id' => 1, 'creator_id' => 1]));

        $filter = new TaskCreatorFilter();
        $filter->setCurrentUserId(1);
        $filter->withQuery($query);
        $filter->withValue('me');
        $filter->apply();

        $this->assertCount(1, $query->findAll());

        $filter = new TaskCreatorFilter();
        $filter->setCurrentUserId(2);
        $filter->withQuery($query);
        $filter->withValue('me');
        $filter->apply();

        $this->assertCount(0, $query->findAll());
    }
}
