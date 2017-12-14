<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../Base.php';

use Jitamin\Filter\TaskAssigneeFilter;
use Jitamin\Filter\TaskTitleFilter;
use Jitamin\Foundation\Filter\LexerBuilder;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\TaskFinderModel;
use Jitamin\Model\TaskModel;
use Jitamin\Model\UserModel;

class LexerBuilderTest extends Base
{
    public function testBuilderThatReturnResult()
    {
        $project = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $taskFinder = new TaskFinderModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $project->create(['name' => 'Project']));
        $this->assertNotFalse($taskModel->create(['project_id' => 1, 'title' => 'Test']));

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
        $taskModel = new TaskModel($this->container);
        $taskFinder = new TaskFinderModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $project->create(['name' => 'Project']));
        $this->assertNotFalse($taskModel->create(['project_id' => 1, 'title' => 'Test']));

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
        $taskModel = new TaskModel($this->container);
        $taskFinder = new TaskFinderModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $project->create(['name' => 'Project']));
        $this->assertNotFalse($taskModel->create(['project_id' => 1, 'title' => 'Test']));

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
        $taskModel = new TaskModel($this->container);
        $taskFinder = new TaskFinderModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $project->create(['name' => 'Project']));
        $this->assertNotFalse($taskModel->create(['project_id' => 1, 'title' => 'ABC', 'owner_id' => 1]));
        $this->assertNotFalse($taskModel->create(['project_id' => 1, 'title' => 'DEF']));

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
        $taskModel = new TaskModel($this->container);
        $taskFinder = new TaskFinderModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(1, $project->create(['name' => 'Project']));
        $this->assertNotFalse($taskModel->create(['project_id' => 1, 'title' => 'Test']));

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
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $userModel = new UserModel($this->container);
        $query = $taskFinder->getExtendedQuery();

        $this->assertEquals(2, $userModel->create(['username' => 'foobar', 'email' => 'foobar@here', 'name' => 'Foo Bar']));
        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskModel->create(['title' => 'Test 1', 'project_id' => 1, 'owner_id' => 2]));
        $this->assertEquals(2, $taskModel->create(['title' => 'Test 2', 'project_id' => 1, 'owner_id' => 1]));
        $this->assertEquals(3, $taskModel->create(['title' => 'Test 3', 'project_id' => 1, 'owner_id' => 0]));

        $builder = new LexerBuilder();
        $builder->withFilter(new TaskAssigneeFilter());
        $builder->withFilter(new TaskTitleFilter(), true);
        $builder->withQuery($query);
        $tasks = $builder->build('assignee:admin assignee:foobar')->toArray();

        $this->assertCount(2, $tasks);
    }
}
