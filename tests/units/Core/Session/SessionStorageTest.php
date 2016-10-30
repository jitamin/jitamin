<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../Base.php';

use Hiject\Core\Session\SessionStorage;

class SessionStorageTest extends Base
{
    public function testNotPersistentStorage()
    {
        $storage = new SessionStorage();
        $storage->something = array('a' => 'b');
        $this->assertEquals(array('a' => 'b'), $storage->something);
        $this->assertTrue(isset($storage->something));
        $this->assertFalse(isset($storage->something->x));
        $this->assertFalse(isset($storage->notFound));
        $this->assertFalse(isset($storage->notFound->x));
        $this->assertFalse(isset($storage->notFound['x']));
    }

    public function testPersistentStorage()
    {
        $session = array('d' => 'e');

        $storage = new SessionStorage();
        $storage->setStorage($session);
        $storage->something = array('a' => 'b');

        $this->assertEquals(array('a' => 'b'), $storage->something);
        $this->assertEquals('e', $storage->d);

        $storage->something['a'] = 'c';
        $this->assertEquals('c', $storage->something['a']);

        $storage = null;
        $this->assertEquals(array('something' => array('a' => 'c'), 'd' => 'e'), $session);
    }

    public function testFlush()
    {
        $session = array('d' => 'e');

        $storage = new SessionStorage();
        $storage->setStorage($session);
        $storage->something = array('a' => 'b');

        $this->assertEquals(array('a' => 'b'), $storage->something);
        $this->assertEquals('e', $storage->d);

        $storage->flush();

        $this->assertFalse(isset($storage->d));
        $this->assertFalse(isset($storage->something));

        $storage->foo = 'bar';

        $storage = null;
        $this->assertEquals(array('foo' => 'bar'), $session);
    }
}
