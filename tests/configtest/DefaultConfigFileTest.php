<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class DefaultConfigFileTest extends PHPUnit_Framework_TestCase
{
    public function testThatFileCanBeImported()
    {
        $this->assertNotFalse(include __DIR__.'/../../config/config.default.php');
    }
}
