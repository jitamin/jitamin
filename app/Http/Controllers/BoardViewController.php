<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Controller;

use Hiject\Core\Controller\AccessForbiddenException;
use Hiject\Formatter\BoardFormatter;
use Hiject\Model\TaskModel;

/**
 * Board controller.
 */
class BoardViewController extends BaseController
{
    /**
     * Display the public version of a board
     * Access checked by a simple token, no user login, read only, auto-refresh.
     */
    public function readonly()
    {
        $token = $this->request->getStringParam('token');
        $project = $this->projectModel->getByToken($token);

        if (empty($project)) {
            throw AccessForbiddenException::getInstance()->withoutLayout();
        }

        $query = $this->taskFinderModel
            ->getExtendedQuery()
            ->eq(TaskModel::TABLE.'.is_active', TaskModel::STATUS_OPEN);

        $this->response->html($this->helper->layout->app('board/view_public', [
            'project'   => $project,
            'swimlanes' => BoardFormatter::getInstance($this->container)
                ->withProjectId($project['id'])
                ->withQuery($query)
                ->format(),
            'title'                          => $project['name'],
            'description'                    => $project['description'],
            'no_layout'                      => true,
            'not_editable'                   => true,
            'board_public_refresh_interval'  => $this->configModel->get('board_public_refresh_interval'),
            'board_private_refresh_interval' => $this->configModel->get('board_private_refresh_interval'),
            'board_highlight_period'         => $this->configModel->get('board_highlight_period'),
        ]));
    }

    /**
     * Show a board for a given project.
     */
    public function show()
    {
        $project = $this->getProject();
        $query = $this->helper->projectHeader->getSearchQuery($project);

        $this->response->html($this->helper->layout->app('board/view_private', [
            'project'                        => $project,
            'title'                          => $project['name'],
            'description'                    => $this->helper->projectHeader->getDescription($project),
            'board_private_refresh_interval' => $this->configModel->get('board_private_refresh_interval'),
            'board_highlight_period'         => $this->configModel->get('board_highlight_period'),
            'swimlanes'                      => $this->taskLexer
                ->build($query)
                ->format(BoardFormatter::getInstance($this->container)->withProjectId($project['id'])),
        ]));
    }
}
