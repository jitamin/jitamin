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

use Hiject\ExternalLink\WebLink;

class WebLinkTest extends Base
{
    public function testGetTitleFromHtml()
    {
        $url = 'https://hiject.net/something';
        $title = 'My title';
        $html = '<!DOCTYPE html><html><head><title>  '.$title.'  </title></head><body>Test</body></html>';

        $webLink = new WebLink($this->container);
        $webLink->setUrl($url);
        $this->assertEquals($url, $webLink->getUrl());

        $this->container['httpClient']
            ->expects($this->once())
            ->method('get')
            ->with($url)
            ->will($this->returnValue($html));

        $this->assertEquals($title, $webLink->getTitle());
    }

    public function testGetTitleFromUrl()
    {
        $url = 'https://hiject.net/something';
        $html = '<!DOCTYPE html><html><head></head><body>Test</body></html>';

        $webLink = new WebLink($this->container);
        $webLink->setUrl($url);
        $this->assertEquals($url, $webLink->getUrl());

        $this->container['httpClient']
            ->expects($this->once())
            ->method('get')
            ->with($url)
            ->will($this->returnValue($html));

        $this->assertEquals('hiject.net/something', $webLink->getTitle());
    }
}
