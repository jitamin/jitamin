<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Core\Filter\OrCriteria;
use Hiject\Filter\TaskAssigneeFilter;
use Hiject\Filter\TaskTitleFilter;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\UserModel;

require_once __DIR__.'/../../Base.php';

class OrCriteriaTest extends Base
{
    public function testWithSameFilter()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskCreationModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $userModel = new UserModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(2, $userModel->create(['username' => 'foobar', 'email' => 'foobar@here', 'name' => 'Foo Bar']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskCreation->create(['title' => 'Test 1', 'project_id' => 1, 'owner_id' => 2]));
        $this->assertEquals(2, $taskCreation->create(['title' => 'Test 2', 'project_id' => 1, 'owner_id' => 1]));
        $this->assertEquals(3, $taskCreation->create(['title' => 'Test 3', 'project_id' => 1, 'owner_id' => 0]));

        $criteria = new OrCriteria();
        $criteria->withQuery($query);
        $criteria->withFilter(TaskAssigneeFilter::getInstance(1));
        $criteria->withFilter(TaskAssigneeFilter::getInstance(2));
        $criteria->apply();

        $this->assertCount(2, $query->findAll());
    }

    public function testWithDifferentFilter()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskCreationModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $userModel = new UserModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(2, $userModel->create(['username' => 'foobar', 'name' => 'Foo Bar']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskCreation->create(['title' => 'ABC', 'project_id' => 1, 'owner_id' => 2]));
        $this->assertEquals(2, $taskCreation->create(['title' => 'DEF', 'project_id' => 1, 'owner_id' => 1]));

        $criteria = new OrCriteria();
        $criteria->withQuery($query);
        $criteria->withFilter(TaskAssigneeFilter::getInstance(1));
        $criteria->withFilter(TaskTitleFilter::getInstance('ABC'));
        $criteria->apply();

        $this->assertCount(2, $query->findAll());
    }
}
