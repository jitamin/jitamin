<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../Base.php';

use Jitamin\Core\Http\Request;

class RequestTest extends Base
{
    public function testGetStringParam()
    {
        $request = new Request($this->container, [], [], [], [], []);
        $this->assertEquals('', $request->getStringParam('myvar'));

        $request = new Request($this->container, [], [], [], [], []);
        $this->assertEquals('default', $request->getStringParam('myvar', 'default'));

        $request = new Request($this->container, [], ['myvar' => 'myvalue'], [], [], []);
        $this->assertEquals('myvalue', $request->getStringParam('myvar'));
    }

    public function testGetIntegerParam()
    {
        $request = new Request($this->container, [], [], [], [], []);
        $this->assertEquals(0, $request->getIntegerParam('myvar'));

        $request = new Request($this->container, [], [], [], [], []);
        $this->assertEquals(5, $request->getIntegerParam('myvar', 5));

        $request = new Request($this->container, [], ['myvar' => 'myvalue'], [], [], []);
        $this->assertEquals(0, $request->getIntegerParam('myvar'));

        $request = new Request($this->container, [], ['myvar' => '123'], [], [], []);
        $this->assertEquals(123, $request->getIntegerParam('myvar'));
    }

    public function testGetValues()
    {
        $request = new Request($this->container, [], [], ['myvar' => 'myvalue'], [], []);
        $this->assertEmpty($request->getValue('myvar'));

        $request = new Request($this->container, [], [], ['myvar' => 'myvalue', 'csrf_token' => $this->container['token']->getCSRFToken()], [], []);
        $this->assertEquals('myvalue', $request->getValue('myvar'));

        $request = new Request($this->container, [], [], ['myvar' => 'myvalue', 'csrf_token' => $this->container['token']->getCSRFToken()], [], []);
        $this->assertEquals(['myvar' => 'myvalue'], $request->getValues());
    }

    public function testGetFileContent()
    {
        $request = new Request($this->container, [], [], [], [], []);
        $this->assertEmpty($request->getFileContent('myfile'));

        $filename = tempnam(sys_get_temp_dir(), 'UnitTest');
        file_put_contents($filename, 'something');

        $request = new Request($this->container, [], [], [], ['myfile' => ['tmp_name' => $filename]], []);
        $this->assertEquals('something', $request->getFileContent('myfile'));

        unlink($filename);
    }

    public function testGetFilePath()
    {
        $request = new Request($this->container, [], [], [], [], []);
        $this->assertEmpty($request->getFilePath('myfile'));

        $request = new Request($this->container, [], [], [], ['myfile' => ['tmp_name' => 'somewhere']], []);
        $this->assertEquals('somewhere', $request->getFilePath('myfile'));
    }

    public function testIsPost()
    {
        $request = new Request($this->container, [], [], [], [], []);
        $this->assertFalse($request->isPost());

        $request = new Request($this->container, ['REQUEST_METHOD' => 'POST'], [], [], [], []);
        $this->assertTrue($request->isPost());
    }

    public function testIsAjax()
    {
        $request = new Request($this->container, [], [], [], [], []);
        $this->assertFalse($request->isAjax());

        $request = new Request($this->container, ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'], [], [], [], []);
        $this->assertTrue($request->isAjax());
    }

    public function testIsHTTPS()
    {
        $request = new Request($this->container, [], [], [], []);
        $this->assertFalse($request->isHTTPS());

        $request = new Request($this->container, ['HTTPS' => ''], [], [], [], []);
        $this->assertFalse($request->isHTTPS());

        $request = new Request($this->container, ['HTTPS' => 'off'], [], [], [], []);
        $this->assertFalse($request->isHTTPS());

        $request = new Request($this->container, ['HTTPS' => 'on'], [], [], [], []);
        $this->assertTrue($request->isHTTPS());

        $request = new Request($this->container, ['HTTPS' => '1'], [], [], [], []);
        $this->assertTrue($request->isHTTPS());

        $request = new Request($this->container, ['HTTP_X_FORWARDED_PROTO' => 'https'], [], [], [], []);
        $this->assertTrue($request->isHTTPS());

        $request = new Request($this->container, ['HTTP_X_FORWARDED_PROTO' => 'http'], [], [], [], []);
        $this->assertFalse($request->isHTTPS());
    }

    public function testGetCookie()
    {
        $request = new Request($this->container, [], [], [], [], []);
        $this->assertEmpty($request->getCookie('mycookie'));

        $request = new Request($this->container, [], [], [], [], ['mycookie' => 'miam']);
        $this->assertEquals('miam', $request->getCookie('mycookie'));
    }

    public function testGetHeader()
    {
        $request = new Request($this->container, [], [], [], [], []);
        $this->assertEmpty($request->getHeader('X-Forwarded-For'));

        $request = new Request($this->container, ['HTTP_X_FORWARDED_FOR' => 'test'], [], [], [], []);
        $this->assertEquals('test', $request->getHeader('X-Forwarded-For'));
    }

    public function testGetRemoteUser()
    {
        $request = new Request($this->container, [], [], [], [], []);
        $this->assertEmpty($request->getRemoteUser());

        $request = new Request($this->container, [REVERSE_PROXY_USER_HEADER => 'test'], [], [], [], []);
        $this->assertEquals('test', $request->getRemoteUser());
    }

    public function testGetQueryString()
    {
        $request = new Request($this->container, [], [], [], [], []);
        $this->assertEmpty($request->getQueryString());

        $request = new Request($this->container, ['QUERY_STRING' => 'k=v'], [], [], [], []);
        $this->assertEquals('k=v', $request->getQueryString());
    }

    public function testGetUri()
    {
        $request = new Request($this->container, [], [], [], [], []);
        $this->assertEmpty($request->getUri());

        $request = new Request($this->container, ['REQUEST_URI' => '/blah'], [], [], [], []);
        $this->assertEquals('/blah', $request->getUri());
    }

    public function testGetUserAgent()
    {
        $request = new Request($this->container, [], [], [], [], []);
        $this->assertEquals('Unknown', $request->getUserAgent());

        $request = new Request($this->container, ['HTTP_USER_AGENT' => 'My browser'], [], [], [], []);
        $this->assertEquals('My browser', $request->getUserAgent());
    }

    public function testGetIpAddress()
    {
        $request = new Request($this->container, [], [], [], [], []);
        $this->assertEquals('Unknown', $request->getIpAddress());

        $request = new Request($this->container, ['HTTP_X_REAL_IP' => '192.168.1.1,127.0.0.1'], [], [], [], []);
        $this->assertEquals('192.168.1.1', $request->getIpAddress());

        $request = new Request($this->container, ['HTTP_X_FORWARDED_FOR' => '192.168.0.1,127.0.0.1'], [], [], [], []);
        $this->assertEquals('192.168.0.1', $request->getIpAddress());

        $request = new Request($this->container, ['REMOTE_ADDR' => '192.168.0.1'], [], [], [], []);
        $this->assertEquals('192.168.0.1', $request->getIpAddress());

        $request = new Request($this->container, ['REMOTE_ADDR' => ''], [], [], [], []);
        $this->assertEquals('Unknown', $request->getIpAddress());
    }
}
