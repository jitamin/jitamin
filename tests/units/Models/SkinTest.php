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

use Jitamin\Model\SkinModel;

class SkinTest extends Base
{
    public function testGetSkins()
    {
        $skinModel = new SkinModel($this->container);
        $this->assertNotEmpty($skinModel->getSkins());
        $this->assertArrayHasKey('default', $skinModel->getSkins());
        $this->assertArrayNotHasKey('', $skinModel->getSkins());

        $this->assertArrayHasKey('', $skinModel->getSkins(true));
        $this->assertContains('Use system skin', $skinModel->getSkins(true));
    }

    public function testGetCurrentSkin()
    {
        $skinModel = new SkinModel($this->container);
        $this->assertEquals('default', $skinModel->getCurrentSkin());

        $this->container['sessionStorage']->user = ['skin' => 'blue'];
        $this->assertEquals('blue', $skinModel->getCurrentSkin());

        $this->container['sessionStorage']->user = ['skin' => 'yellow'];
        $this->assertEquals('yellow', $skinModel->getCurrentSkin());
    }
}
