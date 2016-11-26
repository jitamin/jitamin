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

use Hiject\Model\ColorModel;
use Hiject\Model\ConfigModel;

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
            'name' => 'Light Green',
            'background' => '#dcedc8',
            'border' => '#689f38',
        ];

        $this->assertEquals($expected, $colorModel->getColorProperties('light_green'));

        $expected = [
            'name' => 'Yellow',
            'background' => 'rgb(245, 247, 196)',
            'border' => 'rgb(223, 227, 45)',
        ];

        $this->assertEquals($expected, $colorModel->getColorProperties('foobar'));
    }

    public function testGetList()
    {
        $colorModel = new ColorModel($this->container);

        $colors = $colorModel->getList();
        $this->assertCount(16, $colors);
        $this->assertEquals('Yellow', $colors['yellow']);

        $colors = $colorModel->getList(true);
        $this->assertCount(17, $colors);
        $this->assertEquals('All colors', $colors['']);
        $this->assertEquals('Yellow', $colors['yellow']);
    }

    public function testGetDefaultColor()
    {
        $colorModel = new ColorModel($this->container);
        $configModel = new ConfigModel($this->container);

        $this->assertEquals('yellow', $colorModel->getDefaultColor());

        $this->container['memoryCache']->flush();
        $this->assertTrue($configModel->save(['default_color' => 'red']));
        $this->assertEquals('red', $colorModel->getDefaultColor());
    }

    public function testGetDefaultColors()
    {
        $colorModel = new ColorModel($this->container);

        $colors = $colorModel->getDefaultColors();
        $this->assertCount(16, $colors);
    }

    public function testGetBorderColor()
    {
        $colorModel = new ColorModel($this->container);
        $this->assertEquals('rgb(74, 227, 113)', $colorModel->getBorderColor('green'));
    }

    public function testGetBackgroundColor()
    {
        $colorModel = new ColorModel($this->container);
        $this->assertEquals('rgb(189, 244, 203)', $colorModel->getBackgroundColor('green'));
    }

    public function testGetCss()
    {
        $colorModel = new ColorModel($this->container);
        $css = $colorModel->getCss();

        $this->assertStringStartsWith('div.color-yellow {', $css);
        $this->assertStringEndsWith('td.color-amber { background-color: #ffe082}', $css);
    }
}
