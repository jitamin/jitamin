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

use Hiject\Model\ProjectModel;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\TaskLinkModel;
use Hiject\Validator\TaskLinkValidator;

class TaskLinkValidatorTest extends Base
{
    public function testValidateCreation()
    {
        $taskLinkValidator = new TaskLinkValidator($this->container);
        $taskLinkModel = new TaskLinkModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test']));
        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'A']));
        $this->assertEquals(2, $taskCreationModel->create(['project_id' => 1, 'title' => 'B']));

        $links = $taskLinkModel->getAll(1);
        $this->assertEmpty($links);

        $links = $taskLinkModel->getAll(2);
        $this->assertEmpty($links);

        // Check creation
        $r = $taskLinkValidator->validateCreation(['task_id' => 1, 'link_id' => 1, 'opposite_task_id' => 2]);
        $this->assertTrue($r[0]);

        $r = $taskLinkValidator->validateCreation(['task_id' => 1, 'link_id' => 1]);
        $this->assertFalse($r[0]);

        $r = $taskLinkValidator->validateCreation(['task_id' => 1, 'opposite_task_id' => 2]);
        $this->assertFalse($r[0]);

        $r = $taskLinkValidator->validateCreation(['task_id' => 1, 'opposite_task_id' => 2]);
        $this->assertFalse($r[0]);

        $r = $taskLinkValidator->validateCreation(['task_id' => 1, 'link_id' => 1, 'opposite_task_id' => 1]);
        $this->assertFalse($r[0]);
    }

    public function testValidateModification()
    {
        $taskLinkValidator = new TaskLinkValidator($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test']));
        $this->assertEquals(1, $taskCreationModel->create(['project_id' => 1, 'title' => 'A']));
        $this->assertEquals(2, $taskCreationModel->create(['project_id' => 1, 'title' => 'B']));

        // Check modification
        $r = $taskLinkValidator->validateModification(['id' => 1, 'task_id' => 1, 'link_id' => 1, 'opposite_task_id' => 2]);
        $this->assertTrue($r[0]);

        $r = $taskLinkValidator->validateModification(['id' => 1, 'task_id' => 1, 'link_id' => 1]);
        $this->assertFalse($r[0]);

        $r = $taskLinkValidator->validateModification(['id' => 1, 'task_id' => 1, 'opposite_task_id' => 2]);
        $this->assertFalse($r[0]);

        $r = $taskLinkValidator->validateModification(['id' => 1, 'task_id' => 1, 'opposite_task_id' => 2]);
        $this->assertFalse($r[0]);

        $r = $taskLinkValidator->validateModification(['id' => 1, 'task_id' => 1, 'link_id' => 1, 'opposite_task_id' => 1]);
        $this->assertFalse($r[0]);
    }
}
