<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../Base.php';

use Jitamin\Core\Session\SessionStorage;

class SessionStorageTest extends Base
{
    public function testNotPersistentStorage()
    {
        $storage = new SessionStorage();
        $storage->something = ['a' => 'b'];
        $this->assertEquals(['a' => 'b'], $storage->something);
        $this->assertTrue(isset($storage->something));
        $this->assertFalse(isset($storage->something->x));
        $this->assertFalse(isset($storage->notFound));
        $this->assertFalse(isset($storage->notFound->x));
        $this->assertFalse(isset($storage->notFound['x']));
    }

    public function testPersistentStorage()
    {
        $session = ['d' => 'e'];

        $storage = new SessionStorage();
        $storage->setStorage($session);
        $storage->something = ['a' => 'b'];

        $this->assertEquals(['a' => 'b'], $storage->something);
        $this->assertEquals('e', $storage->d);

        $storage->something['a'] = 'c';
        $this->assertEquals('c', $storage->something['a']);

        $storage = null;
        $this->assertEquals(['something' => ['a' => 'c'], 'd' => 'e'], $session);
    }

    public function testFlush()
    {
        $session = ['d' => 'e'];

        $storage = new SessionStorage();
        $storage->setStorage($session);
        $storage->something = ['a' => 'b'];

        $this->assertEquals(['a' => 'b'], $storage->something);
        $this->assertEquals('e', $storage->d);

        $storage->flush();

        $this->assertFalse(isset($storage->d));
        $this->assertFalse(isset($storage->something));

        $storage->foo = 'bar';

        $storage = null;
        $this->assertEquals(['foo' => 'bar'], $session);
    }
}
