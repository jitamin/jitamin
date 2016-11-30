<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Providers;

use Hiject\Api\Middleware\AuthenticationMiddleware;
use Hiject\Api\Procedure\ActionProcedure;
use Hiject\Api\Procedure\AppProcedure;
use Hiject\Api\Procedure\BoardProcedure;
use Hiject\Api\Procedure\CategoryProcedure;
use Hiject\Api\Procedure\ColumnProcedure;
use Hiject\Api\Procedure\CommentProcedure;
use Hiject\Api\Procedure\GroupMemberProcedure;
use Hiject\Api\Procedure\GroupProcedure;
use Hiject\Api\Procedure\LinkProcedure;
use Hiject\Api\Procedure\MeProcedure;
use Hiject\Api\Procedure\ProjectFileProcedure;
use Hiject\Api\Procedure\ProjectPermissionProcedure;
use Hiject\Api\Procedure\ProjectProcedure;
use Hiject\Api\Procedure\SubtaskProcedure;
use Hiject\Api\Procedure\SubtaskTimeTrackingProcedure;
use Hiject\Api\Procedure\SwimlaneProcedure;
use Hiject\Api\Procedure\TaskExternalLinkProcedure;
use Hiject\Api\Procedure\TaskFileProcedure;
use Hiject\Api\Procedure\TaskLinkProcedure;
use Hiject\Api\Procedure\TaskMetadataProcedure;
use Hiject\Api\Procedure\TaskProcedure;
use Hiject\Api\Procedure\UserProcedure;
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
