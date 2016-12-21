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

use Jitamin\ExternalLink\WebLinkProvider;

class WebLinkProviderTest extends Base
{
    public function testGetName()
    {
        $webLinkProvider = new WebLinkProvider($this->container);
        $this->assertEquals('Web Link', $webLinkProvider->getName());
    }

    public function testGetType()
    {
        $webLinkProvider = new WebLinkProvider($this->container);
        $this->assertEquals('weblink', $webLinkProvider->getType());
    }

    public function testGetDependencies()
    {
        $webLinkProvider = new WebLinkProvider($this->container);
        $this->assertEquals(['related' => 'Related'], $webLinkProvider->getDependencies());
    }

    public function testMatch()
    {
        $webLinkProvider = new WebLinkProvider($this->container);

        $webLinkProvider->setUserTextInput('https://jitamin.net/');
        $this->assertTrue($webLinkProvider->match());

        $webLinkProvider->setUserTextInput('https://jitamin.net/mypage');
        $this->assertTrue($webLinkProvider->match());

        $webLinkProvider->setUserTextInput('  https://jitamin.net/ ');
        $this->assertTrue($webLinkProvider->match());

        $webLinkProvider->setUserTextInput('http:// invalid url');
        $this->assertFalse($webLinkProvider->match());

        $webLinkProvider->setUserTextInput('');
        $this->assertFalse($webLinkProvider->match());
    }

    public function testGetLink()
    {
        $webLinkProvider = new WebLinkProvider($this->container);
        $this->assertInstanceOf('\Jitamin\ExternalLink\WebLink', $webLinkProvider->getLink());
    }
}
