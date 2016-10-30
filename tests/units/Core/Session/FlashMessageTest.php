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

use Hiject\Core\Session\FlashMessage;

class FlashMessageTest extends Base
{
    public function testMessage()
    {
        $flash = new FlashMessage($this->container);

        $flash->success('my message');
        $this->assertEquals('my message', $flash->getMessage('success'));
        $this->assertEmpty($flash->getMessage('success'));

        $flash->failure('my error message');
        $this->assertEquals('my error message', $flash->getMessage('failure'));
        $this->assertEmpty($flash->getMessage('failure'));

        $this->assertEmpty($flash->getMessage('not found'));
    }
}
