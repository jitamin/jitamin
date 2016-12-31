<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Bus\EventBuilder\ProjectFileEventBuilder;
use Jitamin\Model\ProjectFileModel;
use Jitamin\Model\ProjectModel;

require_once __DIR__.'/../Base.php';

class ProjectFileEventBuilderTest extends Base
{
    public function testWithMissingFile()
    {
        $projectFileEventBuilder = new ProjectFileEventBuilder($this->container);
        $projectFileEventBuilder->withFileId(42);
        $this->assertNull($projectFileEventBuilder->buildEvent());
    }

    public function testBuild()
    {
        $projectModel = new ProjectModel($this->container);
        $projectFileModel = new ProjectFileModel($this->container);
        $projectFileEventBuilder = new ProjectFileEventBuilder($this->container);

        $this->assertEquals(1, $projectModel->create(['name' => 'test1']));
        $this->assertEquals(1, $projectFileModel->create(1, 'Test', '/tmp/test', 123));

        $event = $projectFileEventBuilder->withFileId(1)->buildEvent();

        $this->assertInstanceOf('Jitamin\Bus\Event\ProjectFileEvent', $event);
        $this->assertNotEmpty($event['file']);
        $this->assertNotEmpty($event['project']);
    }
}
