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

use Hiject\Core\Helper;

class HelperTest extends Base
{
    public function testRegister()
    {
        $helper = new Helper($this->container);
        $helper->register('foobar', '\Stdclass');

        $this->assertInstanceOf('Stdclass', $helper->foobar);
        $this->assertInstanceOf('Stdclass', $helper->getHelper('foobar'));
    }
}
