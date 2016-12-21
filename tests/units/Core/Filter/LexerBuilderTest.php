<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../Base.php';

use Hiject\Core\Filter\LexerBuilder;
use Hiject\Filter\TaskAssigneeFilter;
use Hiject\Filter\TaskTitleFilter;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskFinderModel;
use Hiject\Model\TaskModel;
use Hiject\Model\UserModel;

class LexerBuilderTest extends Base
{
    public function testBuilderThatReturnResult()
    {
        $project = new ProjectModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $taskFinder = new TaskFinderModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $project->create(['name' => 'Project']));
        $this->assertNotFalse($taskCreation->create(['project_id' => 1, 'title' => 'Test']));

        $builder = new LexerBuilder();
        $builder->withFilter(new TaskAssigneeFilter());
        $builder->withFilter(new TaskTitleFilter(), true);
        $builder->withQuery($query);
        $tasks = $builder->build('assignee:nobody')->toArray();

        $this->assertCount(1, $tasks);
        $this->assertEquals('Test', $tasks[0]['title']);
    }

    public function testBuilderThatReturnNothing()
    {
        $project = new ProjectModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $taskFinder = new TaskFinderModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $project->create(['name' => 'Project']));
        $this->assertNotFalse($taskCreation->create(['project_id' => 1, 'title' => 'Test']));

        $builder = new LexerBuilder();
        $builder->withFilter(new TaskAssigneeFilter());
        $builder->withFilter(new TaskTitleFilter(), true);
        $builder->withQuery($query);
        $tasks = $builder->build('something')->toArray();

        $this->assertCount(0, $tasks);
    }

    public function testBuilderWithEmptyInput()
    {
        $project = new ProjectModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $taskFinder = new TaskFinderModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $project->create(['name' => 'Project']));
        $this->assertNotFalse($taskCreation->create(['project_id' => 1, 'title' => 'Test']));

        $builder = new LexerBuilder();
        $builder->withFilter(new TaskAssigneeFilter());
        $builder->withFilter(new TaskTitleFilter(), true);
        $builder->withQuery($query);
        $tasks = $builder->build('')->toArray();

        $this->assertCount(1, $tasks);
    }

    public function testBuilderWithMultipleMatches()
    {
        $project = new ProjectModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $taskFinder = new TaskFinderModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $project->create(['name' => 'Project']));
        $this->assertNotFalse($taskCreation->create(['project_id' => 1, 'title' => 'ABC', 'owner_id' => 1]));
        $this->assertNotFalse($taskCreation->create(['project_id' => 1, 'title' => 'DEF']));

        $builder = new LexerBuilder();
        $builder->withFilter(new TaskAssigneeFilter());
        $builder->withFilter(new TaskTitleFilter(), true);
        $builder->withQuery($query);
        $tasks = $builder->build('assignee:nobody assignee:1')->toArray();

        $this->assertCount(2, $tasks);
    }

    public function testClone()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $builder = new LexerBuilder();
        $builder->withFilter(new TaskAssigneeFilter());
        $builder->withFilter(new TaskTitleFilter());
        $builder->withQuery($query);

        $clone = clone $builder;
        $this->assertFalse($builder === $clone);
        $this->assertFalse($builder->build('test')->getQuery() === $clone->build('test')->getQuery());
    }

    public function testBuilderWithMixedCaseSearchAttribute()
    {
        $project = new ProjectModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $taskFinder = new TaskFinderModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $project->create(['name' => 'Project']));
        $this->assertNotFalse($taskCreation->create(['project_id' => 1, 'title' => 'Test']));

        $builder = new LexerBuilder();
        $builder->withFilter(new TaskAssigneeFilter());
        $builder->withFilter(new TaskTitleFilter(), true);
        $builder->withQuery($query);
        $tasks = $builder->build('AsSignEe:nobody')->toArray();

        $this->assertCount(1, $tasks);
        $this->assertEquals('Test', $tasks[0]['title']);
    }

    public function testWithOrCriteria()
    {
        $taskFinder = new TaskFinderModel($this->container);
        $taskCreation = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $userModel = new UserModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(2, $userModel->create(['username' => 'foobar', 'email' => 'foobar@here', 'name' => 'Foo Bar']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskCreation->create(['title' => 'Test 1', 'project_id' => 1, 'owner_id' => 2]));
        $this->assertEquals(2, $taskCreation->create(['title' => 'Test 2', 'project_id' => 1, 'owner_id' => 1]));
        $this->assertEquals(3, $taskCreation->create(['title' => 'Test 3', 'project_id' => 1, 'owner_id' => 0]));

        $builder = new LexerBuilder();
        $builder->withFilter(new TaskAssigneeFilter());
        $builder->withFilter(new TaskTitleFilter(), true);
        $builder->withQuery($query);
        $tasks = $builder->build('assignee:admin assignee:foobar')->toArray();

        $this->assertCount(2, $tasks);
        $this->assertEquals('Test 1', $tasks[0]['title']);
        $this->assertEquals('Test 2', $tasks[1]['title']);
    }
}
