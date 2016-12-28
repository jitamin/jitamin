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

    public function testGetLayouts()
    {
        $skinModel = new SkinModel($this->container);
        $this->assertNotEmpty($skinModel->getLayouts());
        $this->assertArrayHasKey('fluid', $skinModel->getLayouts());
        $this->assertArrayNotHasKey('', $skinModel->getLayouts());

        $this->assertArrayHasKey('', $skinModel->getLayouts(true));
        $this->assertContains('Use system layout', $skinModel->getLayouts(true));
    }

    public function testGetCurrentLayout()
    {
        $skinModel = new SkinModel($this->container);
        $this->assertEquals('', $skinModel->getCurrentLayout());

        $this->container['sessionStorage']->user = ['layout' => 'fluid'];
        $this->assertEquals('fluid', $skinModel->getCurrentLayout());

        $this->container['sessionStorage']->user = ['layout' => 'fixed'];
        $this->assertEquals('fixed', $skinModel->getCurrentLayout());
    }
}
