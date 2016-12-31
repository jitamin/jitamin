<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../Base.php';

use Jitamin\Helper\AssetHelper;
use Jitamin\Model\SettingModel;

class AssetHelperTest extends Base
{
    public function testCustomCss()
    {
        $h = new AssetHelper($this->container);
        $c = new SettingModel($this->container);

        $this->assertEmpty($h->customCss());

        $this->assertTrue($c->save(['application_stylesheet' => 'p { color: red }']));
        $this->container['memoryCache']->flush();

        $this->assertEquals('<style>p { color: red }</style>', $h->customCss());
    }
}
