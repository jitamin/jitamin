<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Bus\Job\ProjectFileEventJob;
use Jitamin\Model\ProjectFileModel;
use Jitamin\Model\ProjectModel;

require_once __DIR__.'/../Base.php';

class ProjectFileEventJobTest extends Base
{
    public function testJobParams()
    {
        $projectFileEventJob = new ProjectFileEventJob($this->container);
        $projectFileEventJob->withParams(123, 'foobar');

        $this->assertSame([123, 'foobar'], $projectFileEventJob->getJobParams());
    }

    public function testWithMissingFile()
    {
        $this->container['dispatcher']->addListener(ProjectFileModel::EVENT_CREATE, function () {
        });

        $projectFileEventJob = new ProjectFileEventJob($this->container);
        $projectFileEventJob->execute(42, ProjectFileModel::EVENT_CREATE);

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertEmpty($called);
    }

    public function testTriggerEvents()
    {
        $this->container['dispatcher']->addListener(ProjectFileModel::EVENT_CREATE, function () {
        });

        $projectModel = new ProjectModel($this->container);
        $projectFileModel = new ProjectFileModel($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $projectFileModel->create(1, 'Test', '/tmp/test', 123));

        $called = $this->container['dispatcher']->getCalledListeners();
        $this->assertArrayHasKey(ProjectFileModel::EVENT_CREATE.'.closure', $called);
    }
}
