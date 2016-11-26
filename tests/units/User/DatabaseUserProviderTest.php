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

use Hiject\User\DatabaseUserProvider;

class DatabaseUserProviderTest extends Base
{
    public function testGetInternalId()
    {
        $provider = new DatabaseUserProvider(['id' => 123]);
        $this->assertEquals(123, $provider->getInternalId());
    }
}
