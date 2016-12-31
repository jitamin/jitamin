<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Bus\Job\CommentEventJob;
use Jitamin\Model\CommentModel;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\TaskModel;

require_once __DIR__.'/../Base.php';

class CommentEventJobTest extends Base
{
    public function testJobParams()
    {
        $commentEventJob = new CommentEventJob($this->container);
        $commentEventJob->withParams(123, 'foobar');

        $this->assertSame([123, 'foobar'], $commentEventJob->getJobParams());
    }

    public function testWithMissingComment()
    {
        $this->container['dispatcher']->addListener(CommentModel::EVENT_CREATE, function () {
        });

        $commentEventJob = new CommentEventJob($this->container);
        $commentEventJob->execute(42, CommentModel::EVENT_CREATE);

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertEmpty($called);
    }

    public function testTriggerEvents()
    {
        $this->container['dispatcher']->addListener(CommentModel::EVENT_CREATE, function () {
        });
        $this->container['dispatcher']->addListener(CommentModel::EVENT_UPDATE, function () {
        });
        $this->container['dispatcher']->addListener(CommentModel::EVENT_DELETE, function () {
        });

        $commentModel = new CommentModel($this->container);
        $taskModel = new TaskModel($this->container);
        $projectModel = new ProjectModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskModel->create(['title' => 'test', 'project_id' => 1]));
        $this->assertEquals(1, $commentModel->create(['task_id' => 1, 'comment' => 'foobar', 'user_id' => 1]));
        $this->assertTrue($commentModel->update(['id' => 1, 'comment' => 'test']));
        $this->assertTrue($commentModel->remove(1));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(CommentModel::EVENT_CREATE.'.closure', $called);
        $this->assertArrayHasKey(CommentModel::EVENT_UPDATE.'.closure', $called);
        $this->assertArrayHasKey(CommentModel::EVENT_DELETE.'.closure', $called);
    }
}
