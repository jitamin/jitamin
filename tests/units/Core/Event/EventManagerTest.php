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

use Jitamin\Core\Event\EventManager;

class EventManagerTest extends Base
{
    public function testAddEvent()
    {
        $eventManager = new EventManager();
        $eventManager->register('my.event', 'My Event');

        $events = $eventManager->getAll();
        $this->assertArrayHasKey('my.event', $events);
        $this->assertEquals('My Event', $events['my.event']);
    }
}
