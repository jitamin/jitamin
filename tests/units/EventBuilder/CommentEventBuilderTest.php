<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Bus\EventBuilder\CommentEventBuilder;
use Hiject\Model\CommentModel;
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskCreationModel;

require_once __DIR__.'/../Base.php';

class CommentEventBuilderTest extends Base
{
    public function testWithMissingComment()
    {
        $commentEventBuilder = new CommentEventBuilder($this->container);
        $commentEventBuilder->withCommentId(42);
        $this->assertNull($commentEventBuilder->buildEvent());
    }

    public function testBuild()
    {
        $commentModel = new CommentModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $commentEventBuilder = new CommentEventBuilder($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $taskCreationModel->create(['title' => 'test', 'project_id' => 1]));
        $this->assertEquals(1, $commentModel->create(['task_id' => 1, 'comment' => 'bla bla', 'user_id' => 1]));

        $commentEventBuilder->withCommentId(1);
        $event = $commentEventBuilder->buildEvent();

        $this->assertInstanceOf('Hiject\Bus\Event\CommentEvent', $event);
        $this->assertNotEmpty($event['comment']);
        $this->assertNotEmpty($event['task']);
    }
}
