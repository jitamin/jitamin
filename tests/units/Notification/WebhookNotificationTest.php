<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../Base.php';

use Jitamin\Bus\Subscriber\NotificationSubscriber;
use Jitamin\Model\ProjectModel;
use Jitamin\Model\SettingModel;
use Jitamin\Model\TaskModel;

class WebhookNotificationTest extends Base
{
    public function testTaskCreation()
    {
        $settingModel = new SettingModel($this->container);
        $projectModel = new ProjectModel($this->container);
        $taskModel = new TaskModel($this->container);
        $this->container['dispatcher']->addSubscriber(new NotificationSubscriber($this->container));

        $settingModel->save(['webhook_url' => 'http://localhost/?task-creation']);

        $this->container['httpClient']
            ->expects($this->once())
            ->method('postJson')
            ->with($this->stringContains('http://localhost/?task-creation&token='), $this->anything());

        $this->assertEquals(1, $projectModel->create(['name' => 'test']));
        $this->assertEquals(1, $taskModel->create(['project_id' => 1, 'title' => 'test']));
    }
}
