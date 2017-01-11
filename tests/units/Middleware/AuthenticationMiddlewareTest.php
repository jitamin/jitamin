<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Http\Middleware\AuthenticationMiddleware;

require_once __DIR__.'/../Base.php';

class AuthenticationMiddlewareTest extends Base
{
    /**
     * @var AuthenticationMiddleware
     */
    private $middleware;
    private $nextMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->container['authenticationManager'] = $this
            ->getMockBuilder('Jitamin\Foundation\Security\AuthenticationManager')
            ->setConstructorArgs([$this->container])
            ->setMethods(['checkCurrentSession'])
            ->getMock();

        $this->container['applicationAuthorization'] = $this
            ->getMockBuilder('Jitamin\Foundation\Security\AccessMap')
            ->setMethods(['isAllowed'])
            ->getMock();

        $this->container['response'] = $this
            ->getMockBuilder('Jitamin\Foundation\Http\Response')
            ->setConstructorArgs([$this->container])
            ->setMethods(['redirect'])
            ->getMock();

        $this->container['userSession'] = $this
            ->getMockBuilder('Jitamin\Foundation\Identity\UserSession')
            ->setConstructorArgs([$this->container])
            ->setMethods(['isLogged'])
            ->getMock();

        $this->nextMiddleware = $this
            ->getMockBuilder('Jitamin\Http\Middleware\AuthenticationMiddleware')
            ->setConstructorArgs([$this->container])
            ->setMethods(['execute'])
            ->getMock();

        $this->middleware = new AuthenticationMiddleware($this->container);
        $this->middleware->setNextMiddleware($this->nextMiddleware);
    }

    public function testWithBadSession()
    {
        $this->container['authenticationManager']
            ->expects($this->once())
            ->method('checkCurrentSession')
            ->will($this->returnValue(false));

        $this->nextMiddleware
            ->expects($this->never())
            ->method('execute');

        $this->setExpectedException('Jitamin\Foundation\Exceptions\AccessForbiddenException');
        $this->middleware->execute();
    }

    public function testWithPublicAction()
    {
        $this->container['authenticationManager']
            ->expects($this->once())
            ->method('checkCurrentSession')
            ->will($this->returnValue(true));

        $this->container['applicationAuthorization']
            ->expects($this->once())
            ->method('isAllowed')
            ->will($this->returnValue(true));

        $this->nextMiddleware
            ->expects($this->never())
            ->method('execute');

        $this->middleware->execute();
    }

    public function testWithNotAuthenticatedUser()
    {
        $this->container['authenticationManager']
            ->expects($this->once())
            ->method('checkCurrentSession')
            ->will($this->returnValue(true));

        $this->container['applicationAuthorization']
            ->expects($this->once())
            ->method('isAllowed')
            ->will($this->returnValue(false));

        $this->container['userSession']
            ->expects($this->once())
            ->method('isLogged')
            ->will($this->returnValue(false));

        $this->container['response']
            ->expects($this->once())
            ->method('redirect');

        $this->nextMiddleware
            ->expects($this->never())
            ->method('execute');

        $this->middleware->execute();
    }

    public function testWithAuthenticatedUser()
    {
        $this->container['authenticationManager']
            ->expects($this->once())
            ->method('checkCurrentSession')
            ->will($this->returnValue(true));

        $this->container['applicationAuthorization']
            ->expects($this->once())
            ->method('isAllowed')
            ->will($this->returnValue(false));

        $this->container['userSession']
            ->expects($this->once())
            ->method('isLogged')
            ->will($this->returnValue(true));

        $this->container['response']
            ->expects($this->never())
            ->method('redirect');

        $this->nextMiddleware
            ->expects($this->once())
            ->method('execute');

        $this->middleware->execute();
    }
}
