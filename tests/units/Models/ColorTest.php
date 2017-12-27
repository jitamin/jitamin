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

use Jitamin\Model\ColorModel;
use Jitamin\Model\SettingModel;

class ColorTest extends Base
{
    public function testFind()
    {
        $colorModel = new ColorModel($this->container);
        $this->assertEquals('yellow', $colorModel->find('yellow'));
        $this->assertEquals('yellow', $colorModel->find('Yellow'));
        $this->assertEquals('dark_grey', $colorModel->find('Dark Grey'));
        $this->assertEquals('dark_grey', $colorModel->find('dark_grey'));
    }

    public function testGetColorProperties()
    {
        $colorModel = new ColorModel($this->container);
        $expected = [
            'name'              => 'Light Green',
            'border-left-color' => '#DCEDC8',
            'border-width'      => '3px',
        ];

        $this->assertEquals($expected, $colorModel->getColorProperties('light_green'));

        $expected = [
            'name'              => 'Yellow',
            'border-left-color' => '#F5F7C4',
            'border-width'      => '3px',
        ];

        $this->assertEquals($expected, $colorModel->getColorProperties('foobar'));
    }

    public function testGetList()
    {
        $colorModel = new ColorModel($this->container);

        $colors = $colorModel->getList();
        $this->assertCount(17, $colors);
        $this->assertEquals('Yellow', $colors['yellow']);

        $colors = $colorModel->getList(true);
        $this->assertCount(18, $colors);
        $this->assertEquals('All colors', $colors['']);
        $this->assertEquals('Yellow', $colors['yellow']);
    }

    public function testGetDefaultColor()
    {
        $colorModel = new ColorModel($this->container);
        $settingModel = new SettingModel($this->container);

        $this->assertEquals('yellow', $colorModel->getDefaultColor());

        $this->container['memoryCache']->flush();
        $this->assertTrue($settingModel->save(['default_color' => 'red']));
        $this->assertEquals('red', $colorModel->getDefaultColor());
    }

    public function testGetDefaultColors()
    {
        $colorModel = new ColorModel($this->container);

        $colors = $colorModel->getDefaultColors();
        $this->assertCount(17, $colors);
    }

    public function testGetBorderWidth()
    {
        $colorModel = new ColorModel($this->container);
        $this->assertEquals('3px', $colorModel->getBorderWidth('green'));
    }

    public function testGetBorderLeftColor()
    {
        $colorModel = new ColorModel($this->container);
        $this->assertEquals('#BDF4CB', $colorModel->getBorderLeftColor('green'));
    }

    public function testGetCss()
    {
        $colorModel = new ColorModel($this->container);
        $css = $colorModel->getCss();

        $this->assertStringStartsWith('div.color-white {', $css);
        $this->assertStringEndsWith('td.color-amber { background-color: #CCCCCC}', $css);
    }
}
