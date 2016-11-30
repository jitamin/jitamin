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

use Hiject\Auth\TotpAuth;

class TotpAuthTest extends Base
{
    public function testGetName()
    {
        $provider = new TotpAuth($this->container);
        $this->assertEquals('Time-based One-time Password Algorithm', $provider->getName());
    }

    public function testGetSecret()
    {
        $provider = new TotpAuth($this->container);
        $this->assertEmpty($provider->getSecret());

        $provider->generateSecret();
        $secret = $provider->getSecret();

        $this->assertNotEmpty($secret);
        $this->assertEquals($secret, $provider->getSecret());
        $this->assertEquals($secret, $provider->getSecret());
    }

    public function testSetSecret()
    {
        $provider = new TotpAuth($this->container);
        $provider->setSecret('mySecret');
        $this->assertEquals('mySecret', $provider->getSecret());
    }

    public function testGetUrl()
    {
        $provider = new TotpAuth($this->container);

        $this->assertEmpty($provider->getQrCodeUrl('me'));
        $this->assertEmpty($provider->getKeyUrl('me'));

        $provider->setSecret('mySecret');
        $this->assertEquals(
            'https://chart.googleapis.com/chart?chs=200x200&cht=qr&chld=M|0&chl=otpauth%3A%2F%2Ftotp%2Fme%3Fsecret%3DmySecret%26issuer%3DHiject',
            $provider->getQrCodeUrl('me')
        );

        $this->assertEquals('otpauth://totp/me?secret=mySecret&issuer=Hiject', $provider->getKeyUrl('me'));
    }

    public function testAuthentication()
    {
        $provider = new TotpAuth($this->container);

        $secret = $provider->generateSecret();
        $this->assertNotEmpty($secret);

        $provider->setCode('1234');
        $this->assertFalse($provider->authenticate());

        if ((bool) `which oathtool`) {
            $code = shell_exec('oathtool --totp -b '.$secret);
            $provider->setCode(trim($code));
            $this->assertTrue($provider->authenticate());
        }
    }
}
