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

use Hiject\Helper\AssetHelper;
use Hiject\Model\ConfigModel;

class AssetHelperTest extends Base
{
    public function testCustomCss()
    {
        $h = new AssetHelper($this->container);
        $c = new ConfigModel($this->container);

        $this->assertEmpty($h->customCss());

        $this->assertTrue($c->save(array('application_stylesheet' => 'p { color: red }')));
        $this->container['memoryCache']->flush();

        $this->assertEquals('<style>p { color: red }</style>', $h->customCss());
    }
}
