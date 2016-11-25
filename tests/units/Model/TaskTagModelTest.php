<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Model\ProjectModel;
use Hiject\Model\TagModel;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\TaskTagModel;

require_once __DIR__.'/../Base.php';

class TaskTagModelTest extends Base
{
    public function testAssociationAndDissociation()
    {
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskTagModel = new TaskTagModel($this->container);
        $tagModel = new TagModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'test']));

        $this->assertEquals(1, $tagModel->create(0, 'My tag 1'));
        $this->assertEquals(2, $tagModel->create(0, 'My tag 2'));

        $this->assertTrue($taskTagModel->save(1, 1, ['My tag 1', 'My tag 2', '', 'My tag 3']));

        $tags = $taskTagModel->getTagsByTask(1);
        $this->assertCount(3, $tags);

        $this->assertEquals(1, $tags[0]['id']);
        $this->assertEquals('My tag 1', $tags[0]['name']);

        $this->assertEquals(2, $tags[1]['id']);
        $this->assertEquals('My tag 2', $tags[1]['name']);

        $this->assertEquals(3, $tags[2]['id']);
        $this->assertEquals('My tag 3', $tags[2]['name']);

        $this->assertTrue($taskTagModel->save(1, 1, ['My tag 3', 'My tag 1', 'My tag 4']));

        $tags = $taskTagModel->getTagsByTask(1);
        $this->assertCount(3, $tags);

        $this->assertEquals(1, $tags[0]['id']);
        $this->assertEquals('My tag 1', $tags[0]['name']);

        $this->assertEquals(3, $tags[1]['id']);
        $this->assertEquals('My tag 3', $tags[1]['name']);

        $this->assertEquals(4, $tags[2]['id']);
        $this->assertEquals('My tag 4', $tags[2]['name']);

        $tags = $tagModel->getAll();
        $this->assertCount(4, $tags);
        $this->assertEquals('My tag 1', $tags[0]['name']);
        $this->assertEquals(0, $tags[0]['project_id']);

        $this->assertEquals('My tag 2', $tags[1]['name']);
        $this->assertEquals(0, $tags[1]['project_id']);

        $this->assertEquals('My tag 3', $tags[2]['name']);
        $this->assertEquals(1, $tags[2]['project_id']);

        $this->assertEquals('My tag 4', $tags[3]['name']);
        $this->assertEquals(1, $tags[3]['project_id']);
    }

    public function testGetTagsForTasks()
    {
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskTagModel = new TaskTagModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'test1']));
        $this->assertEquals(2, $taskCreationModel->create(['project_id' => 1, 'title' => 'test2']));
        $this->assertEquals(3, $taskCreationModel->create(['project_id' => 1, 'title' => 'test3']));

        $this->assertTrue($taskTagModel->save(1, 1, ['My tag 1', 'My tag 2', 'My tag 3']));
        $this->assertTrue($taskTagModel->save(1, 2, ['My tag 3']));

        $tags = $taskTagModel->getTagsByTasks([1, 2, 3]);

        $expected = [
            1 => [
                [
                    'id' => 1,
                    'name' => 'My tag 1',
                    'task_id' => 1
                ],
                [
                    'id' => 2,
                    'name' => 'My tag 2',
                    'task_id' => 1
                ],
                [
                    'id' => 3,
                    'name' => 'My tag 3',
                    'task_id' => 1
                ],
            ],
            2 => [
                [
                    'id' => 3,
                    'name' => 'My tag 3',
                    'task_id' => 2,
                ]
            ]
        ];

        $this->assertEquals($expected, $tags);
    }

    public function testGetTagsForTasksWithEmptyList()
    {
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskTagModel = new TaskTagModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'Test']));
        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'test1']));
        $this->assertTrue($taskTagModel->save(1, 1, ['My tag 1', 'My tag 2', 'My tag 3']));

        $tags = $taskTagModel->getTagsByTasks([]);
        $this->assertEquals([], $tags);
    }

    public function testGetTagIdNotAvailableInDestinationProject()
    {
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $taskTagModel = new TaskTagModel($this->container);
        $tagModel = new TagModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'P1']));
        $this->assertEquals(2, $projectModel->create(['name' => 'P2']));
        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'test1']));

        $this->assertEquals(1, $tagModel->create(0, 'T0'));
        $this->assertEquals(2, $tagModel->create(2, 'T1'));
        $this->assertEquals(3, $tagModel->create(2, 'T3'));
        $this->assertEquals(4, $tagModel->create(1, 'T2'));
        $this->assertEquals(5, $tagModel->create(1, 'T3'));
        $this->assertTrue($taskTagModel->save(1, 1, ['T0', 'T2', 'T3']));

        $this->assertEquals([4, 5], $taskTagModel->getTagIdsByTaskNotAvailableInProject(1, 2));
    }
}
