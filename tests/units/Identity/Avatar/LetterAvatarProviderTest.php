<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../Base.php';

use Hiject\Services\Identity\Avatar\LetterAvatarProvider;

class LetterAvatarProviderTest extends Base
{
    public function testGetBackgroundColor()
    {
        $provider = new LetterAvatarProvider($this->container);
        $rgb = $provider->getBackgroundColor('Test');
        $this->assertEquals([107, 83, 172], $rgb);
    }

    public function testIsActive()
    {
        $provider = new LetterAvatarProvider($this->container);
        $this->assertTrue($provider->isActive([]));
    }

    public function testRenderWithFullName()
    {
        $provider = new LetterAvatarProvider($this->container);
        $user = ['id' => 123, 'name' => 'Hiject Admin', 'username' => 'bob', 'email' => ''];
        $expected = '<div class="avatar-letter" style="background-color: rgb(172, 129, 83)" title="Hiject Admin">HA</div>';
        $this->assertEquals($expected, $provider->render($user, 48));
    }

    public function testRenderWithUsername()
    {
        $provider = new LetterAvatarProvider($this->container);
        $user = ['id' => 123, 'name' => '', 'username' => 'admin', 'email' => ''];
        $expected = '<div class="avatar-letter" style="background-color: rgb(134, 45, 132)" title="admin">A</div>';
        $this->assertEquals($expected, $provider->render($user, 48));
    }

    public function testRenderWithUTF8()
    {
        $provider = new LetterAvatarProvider($this->container);
        $user = ['id' => 123, 'name' => 'ü', 'username' => 'admin', 'email' => ''];
        $expected = '<div class="avatar-letter" style="background-color: rgb(62, 147, 31)" title="ü">Ü</div>';
        $this->assertEquals($expected, $provider->render($user, 48));
    }
}
