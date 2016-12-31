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

use Jitamin\Api\Middleware\AuthenticationMiddleware;
use Jitamin\Api\Procedure\ActionProcedure;
use Jitamin\Api\Procedure\AppProcedure;
use Jitamin\Api\Procedure\BoardProcedure;
use Jitamin\Api\Procedure\CategoryProcedure;
use Jitamin\Api\Procedure\ColumnProcedure;
use Jitamin\Api\Procedure\CommentProcedure;
use Jitamin\Api\Procedure\GroupMemberProcedure;
use Jitamin\Api\Procedure\GroupProcedure;
use Jitamin\Api\Procedure\LinkProcedure;
use Jitamin\Api\Procedure\MeProcedure;
use Jitamin\Api\Procedure\ProjectFileProcedure;
use Jitamin\Api\Procedure\ProjectPermissionProcedure;
use Jitamin\Api\Procedure\ProjectProcedure;
use Jitamin\Api\Procedure\SubtaskProcedure;
use Jitamin\Api\Procedure\SubtaskTimeTrackingProcedure;
use Jitamin\Api\Procedure\SwimlaneProcedure;
use Jitamin\Api\Procedure\TaskExternalLinkProcedure;
use Jitamin\Api\Procedure\TaskFileProcedure;
use Jitamin\Api\Procedure\TaskLinkProcedure;
use Jitamin\Api\Procedure\TaskMetadataProcedure;
use Jitamin\Api\Procedure\TaskProcedure;
use Jitamin\Api\Procedure\UserProcedure;
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
            ->withMiddleware(new AuthenticationMiddleware($container));

        $server->getProcedureHandler()
            ->withObject(new MeProcedure($container))
            ->withObject(new ActionProcedure($container))
            ->withObject(new AppProcedure($container))
            ->withObject(new BoardProcedure($container))
            ->withObject(new ColumnProcedure($container))
            ->withObject(new CategoryProcedure($container))
            ->withObject(new CommentProcedure($container))
            ->withObject(new TaskFileProcedure($container))
            ->withObject(new ProjectFileProcedure($container))
            ->withObject(new LinkProcedure($container))
            ->withObject(new ProjectProcedure($container))
            ->withObject(new ProjectPermissionProcedure($container))
            ->withObject(new SubtaskProcedure($container))
            ->withObject(new SubtaskTimeTrackingProcedure($container))
            ->withObject(new SwimlaneProcedure($container))
            ->withObject(new TaskProcedure($container))
            ->withObject(new TaskLinkProcedure($container))
            ->withObject(new TaskExternalLinkProcedure($container))
            ->withObject(new TaskMetadataProcedure($container))
            ->withObject(new UserProcedure($container))
            ->withObject(new GroupProcedure($container))
            ->withObject(new GroupMemberProcedure($container))
            ->withBeforeMethod('beforeProcedure');

        $container['api'] = $server;

        return $container;
    }
}
