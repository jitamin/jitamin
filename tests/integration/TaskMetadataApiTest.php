<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/BaseApiTest.php';

class TaskMetadataApiTest extends BaseApiTest
{
    protected $projectName = 'My project to test tasks metadata';
    protected $metaKey = 'MyTestMetaKey';

    public function testAll()
    {
        $this->assertCreateTeamProject();
        $this->assertCreateTask();
        $this->assertSaveTaskMetadata();
        $this->assertGetTaskMetadata();
        $this->assertGetTaskMetadataByName();
        $this->assertRemoveTaskMetadata();
    }

    public function assertSaveTaskMetadata()
    {
        $this->assertTrue($this->app->saveTaskMetadata($this->taskId, [$this->metaKey => 'metaValue1']));
    }

    public function assertGetTaskMetadata()
    {
        $metaData = $this->app->getTaskMetadata(($this->taskId));
        $this->assertArrayHasKey($this->metaKey, $metaData);
        $this->assertEquals('metaValue1', $metaData[$this->metaKey]);
    }

    public function assertGetTaskMetadataByName()
    {
        $metaValue = $this->app->getTaskMetadataByName($this->taskId, $this->metaKey);
        $this->assertEquals('metaValue1', $metaValue, 'Did not return correct metadata value');
    }

    public function assertRemoveTaskMetadata()
    {
        $result = $this->app->removeTaskMetadata($this->taskId, $this->metaKey);
        $this->assertTrue($result, 'Did not remove metakey with success');
        $metaValue = $this->app->getTaskMetadataByName($this->taskId, $this->metaKey);
        $this->assertEquals('', $metaValue, 'Did not return an empty string due to metadata being deleted');
    }
}
