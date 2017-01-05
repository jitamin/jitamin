<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Jitamin\Middleware\ApplicationAuthorizationMiddleware;

require_once __DIR__.'/../Base.php';

class ApplicationAuthorizationMiddlewareMiddlewareTest extends Base
{
    /**
     * @var ApplicationAuthorizationMiddleware
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
            ->setMethods(['hasAccess'])
            ->getMock();

        $this->nextMiddleware = $this
            ->getMockBuilder('Jitamin\Middleware\ApplicationAuthorizationMiddleware')
            ->setConstructorArgs([$this->container])
            ->setMethods(['execute'])
            ->getMock();

        $this->middleware = new ApplicationAuthorizationMiddleware($this->container);
        $this->middleware->setNextMiddleware($this->nextMiddleware);
    }

    public function testWithAccessDenied()
    {
        $this->container['helper']->user
            ->expects($this->once())
            ->method('hasAccess')
            ->will($this->returnValue(false));

        $this->nextMiddleware
            ->expects($this->never())
            ->method('execute');

        $this->setExpectedException('Jitamin\Core\Controller\AccessForbiddenException');
        $this->middleware->execute();
    }

    public function testWithAccessGranted()
    {
        $this->container['helper']->user
            ->expects($this->once())
            ->method('hasAccess')
            ->will($this->returnValue(true));

        $this->nextMiddleware
            ->expects($this->once())
            ->method('execute');

        $this->middleware->execute();
    }
}
