<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Providers;

use Jitamin\Http\Controllers\Api\ActionController;
use Jitamin\Http\Controllers\Api\AppController;
use Jitamin\Http\Controllers\Api\BoardController;
use Jitamin\Http\Controllers\Api\CategoryController;
use Jitamin\Http\Controllers\Api\ColumnController;
use Jitamin\Http\Controllers\Api\CommentController;
use Jitamin\Http\Controllers\Api\GroupController;
use Jitamin\Http\Controllers\Api\GroupMemberController;
use Jitamin\Http\Controllers\Api\LinkController;
use Jitamin\Http\Controllers\Api\MeController;
use Jitamin\Http\Controllers\Api\ProjectController;
use Jitamin\Http\Controllers\Api\ProjectFileController;
use Jitamin\Http\Controllers\Api\ProjectPermissionController;
use Jitamin\Http\Controllers\Api\SubtaskController;
use Jitamin\Http\Controllers\Api\SubtaskTimeTrackingController;
use Jitamin\Http\Controllers\Api\SwimlaneController;
use Jitamin\Http\Controllers\Api\TaskController;
use Jitamin\Http\Controllers\Api\TaskExternalLinkController;
use Jitamin\Http\Controllers\Api\TaskFileController;
use Jitamin\Http\Controllers\Api\TaskLinkController;
use Jitamin\Http\Controllers\Api\TaskMetadataController;
use Jitamin\Http\Controllers\Api\UserController;
use Jitamin\Http\Middleware\ApiAuthenticationMiddleware;
use JsonRPC\Server;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class of Api Service Provider.
 */
class ApiServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * @param Container $container
     *
     * @return Container
     */
    public function register(Container $container)
    {
        $server = new Server();
        $server->setAuthenticationHeader(API_AUTHENTICATION_HEADER);
        $server->getMiddlewareHandler()
            ->withMiddleware(new ApiAuthenticationMiddleware($container));

        $server->getProcedureHandler()
            ->withObject(new MeController($container))
            ->withObject(new ActionController($container))
            ->withObject(new AppController($container))
            ->withObject(new BoardController($container))
            ->withObject(new ColumnController($container))
            ->withObject(new CategoryController($container))
            ->withObject(new CommentController($container))
            ->withObject(new TaskFileController($container))
            ->withObject(new ProjectFileController($container))
            ->withObject(new LinkController($container))
            ->withObject(new ProjectController($container))
            ->withObject(new ProjectPermissionController($container))
            ->withObject(new SubtaskController($container))
            ->withObject(new SubtaskTimeTrackingController($container))
            ->withObject(new SwimlaneController($container))
            ->withObject(new TaskController($container))
            ->withObject(new TaskLinkController($container))
            ->withObject(new TaskExternalLinkController($container))
            ->withObject(new TaskMetadataController($container))
            ->withObject(new UserController($container))
            ->withObject(new GroupController($container))
            ->withObject(new GroupMemberController($container))
            ->withBeforeMethod('beforeAction');

        $container['api'] = $server;

        return $container;
    }
}
