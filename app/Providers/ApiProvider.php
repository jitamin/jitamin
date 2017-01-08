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

use Jitamin\Controller\Api\ActionController;
use Jitamin\Controller\Api\AppController;
use Jitamin\Controller\Api\BoardController;
use Jitamin\Controller\Api\CategoryController;
use Jitamin\Controller\Api\ColumnController;
use Jitamin\Controller\Api\CommentController;
use Jitamin\Controller\Api\GroupController;
use Jitamin\Controller\Api\GroupMemberController;
use Jitamin\Controller\Api\LinkController;
use Jitamin\Controller\Api\MeController;
use Jitamin\Controller\Api\ProjectController;
use Jitamin\Controller\Api\ProjectFileController;
use Jitamin\Controller\Api\ProjectPermissionController;
use Jitamin\Controller\Api\SubtaskController;
use Jitamin\Controller\Api\SubtaskTimeTrackingController;
use Jitamin\Controller\Api\SwimlaneController;
use Jitamin\Controller\Api\TaskController;
use Jitamin\Controller\Api\TaskExternalLinkController;
use Jitamin\Controller\Api\TaskFileController;
use Jitamin\Controller\Api\TaskLinkController;
use Jitamin\Controller\Api\TaskMetadataController;
use Jitamin\Controller\Api\UserController;
use Jitamin\Middleware\ApiAuthenticationMiddleware;
use JsonRPC\Server;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ApiProvider.
 */
class ApiProvider implements ServiceProviderInterface
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
            ->withBeforeMethod('beforeController');

        $container['api'] = $server;

        return $container;
    }
}
