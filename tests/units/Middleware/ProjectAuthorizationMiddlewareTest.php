<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Http\Middleware\ProjectAuthorizationMiddleware;

require_once __DIR__.'/../Base.php';

class ProjectAuthorizationMiddlewareMiddlewareTest extends Base
{
    /**
     * @var ProjectAuthorizationMiddleware
     */
    private $middleware;
    private $nextMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->container['helper'] = new stdClass();

        $this->container['helper']->user = $this
            ->getMockBuilder('Jitamin\Helper\UserHelper')
            ->setConstructorArgs([$this->container])
            ->setMethods(['hasProjectAccess'])
            ->getMock();

        $this->container['request'] = $this
            ->getMockBuilder('Jitamin\Foundation\Http\Request')
            ->setConstructorArgs([$this->container])
            ->setMethods(['getIntegerParam'])
            ->getMock();

        $this->nextMiddleware = $this
            ->getMockBuilder('Jitamin\Http\Middleware\ProjectAuthorizationMiddleware')
            ->setConstructorArgs([$this->container])
            ->setMethods(['execute'])
            ->getMock();

        $this->middleware = new ProjectAuthorizationMiddleware($this->container);
        $this->middleware->setNextMiddleware($this->nextMiddleware);
    }

    public function testWithAccessDenied()
    {
        $this->container['request']
            ->expects($this->any())
            ->method('getIntegerParam')
            ->will($this->returnValue(123));

        $this->container['helper']->user
            ->expects($this->once())
            ->method('hasProjectAccess')
            ->will($this->returnValue(false));

        $this->nextMiddleware
            ->expects($this->never())
            ->method('execute');

        $this->setExpectedException('Jitamin\Foundation\Exceptions\AccessForbiddenException');
        $this->middleware->execute();
    }

    public function testWithAccessGranted()
    {
        $this->container['request']
            ->expects($this->any())
            ->method('getIntegerParam')
            ->will($this->returnValue(123));

        $this->container['helper']->user
            ->expects($this->once())
            ->method('hasProjectAccess')
            ->will($this->returnValue(true));

        $this->nextMiddleware
            ->expects($this->once())
            ->method('execute');

        $this->middleware->execute();
    }
}
