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

use Hiject\Model\ConfigModel;
use Hiject\Model\TaskCreationModel;
use Hiject\Model\ProjectModel;
use Hiject\Bus\Subscriber\NotificationSubscriber;

class WebhookNotificationTest extends Base
{
    public function testTaskCreation()
    {
        $configModel = new ConfigModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskCreationModel = new TaskCreationModel($this->container);
        $this->container['dispatcher']->addSubscriber(new NotificationSubscriber($this->container));

        $configModel->save(array('webhook_url' => 'http://localhost/?task-creation'));

        $this->container['httpClient']
            ->expects($this->once())
            ->method('postJson')
            ->with($this->stringContains('http://localhost/?task-creation&token='), $this->anything());

        $this->assertEquals(1, $projectModel->create(array('name' => 'test')));
        $this->assertEquals(1, $taskCreationModel->create(array('project_id' => 1, 'title' => 'test')));
    }
}
