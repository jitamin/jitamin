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

use Jitamin\Helper\FileHelper;

class FileHelperTest extends Base
{
    public function testIcon()
    {
        $helper = new FileHelper($this->container);
        $this->assertEquals('fa-file-image-o', $helper->icon('test.png'));
        $this->assertEquals('fa-file-o', $helper->icon('test'));
    }

    public function testGetMimeType()
    {
        $helper = new FileHelper($this->container);

        $this->assertEquals('image/jpeg', $helper->getImageMimeType('My File.JPG'));
        $this->assertEquals('image/jpeg', $helper->getImageMimeType('My File.jpeg'));
        $this->assertEquals('image/png', $helper->getImageMimeType('My File.PNG'));
        $this->assertEquals('image/gif', $helper->getImageMimeType('My File.gif'));
        $this->assertEquals('image/jpeg', $helper->getImageMimeType('My File.bmp'));
        $this->assertEquals('image/jpeg', $helper->getImageMimeType('My File'));
    }

    public function testGetPreviewType()
    {
        $helper = new FileHelper($this->container);
        $this->assertEquals('text', $helper->getPreviewType('test.txt'));
        $this->assertEquals('markdown', $helper->getPreviewType('test.markdown'));
        $this->assertEquals('md', $helper->getPreviewType('test.md'));
        $this->assertEquals(null, $helper->getPreviewType('test.doc'));
    }

    public function testGetBrowserViewType()
    {
        $helper = new FileHelper($this->container);
        $this->assertEquals('pdf', $helper->getBrowserViewType('Jitamin.pdf'));
        $this->assertEquals(null, $helper->getBrowserViewType('Jitamin.doc'));
    }
}
