<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Project\Board;

use Jitamin\Controller\BaseController;
use Jitamin\Core\Controller\AccessForbiddenException;
use Jitamin\Formatter\BoardFormatter;
use Jitamin\Model\UserMetadataModel;

/**
 * Class BoardAjaxController.
 */
class BoardAjaxController extends BaseController
{
    /**
     * Save new task positions (Ajax request made by the drag and drop).
     */
    public function store()
    {
        $project_id = $this->request->getIntegerParam('project_id');

        if (!$project_id || !$this->request->isAjax()) {
            throw new AccessForbiddenException();
        }

        $values = $this->request->getJson();

        if (!$this->helper->projectRole->canMoveTask($project_id, $values['src_column_id'], $values['dst_column_id'])) {
            throw new AccessForbiddenException(e("You don't have the permission to move this task"));
        }

        $result = $this->taskPositionModel->movePosition(
            $project_id,
            $values['task_id'],
            $values['dst_column_id'],
            $values['position'],
            $values['swimlane_id']
        );

        if (!$result) {
            $this->response->status(400);
        } else {
            $this->response->html($this->renderBoard($project_id), 201);
        }
    }

    /**
     * Check if the board have been changed.
     */
    public function check()
    {
        $project_id = $this->request->getIntegerParam('project_id');
        $timestamp = $this->request->getIntegerParam('timestamp');

        if (!$project_id || !$this->request->isAjax()) {
            throw new AccessForbiddenException();
        } elseif (!$this->projectModel->isModifiedSince($project_id, $timestamp)) {
            $this->response->status(304);
        } else {
            $this->response->html($this->renderBoard($project_id));
        }
    }

    /**
     * Reload the board with new filters.
     */
    public function reload()
    {
        $project_id = $this->request->getIntegerParam('project_id');

        if (!$project_id || !$this->request->isAjax()) {
            throw new AccessForbiddenException();
        }

        $values = $this->request->getJson();
        $this->userSession->setFilters($project_id, empty($values['search']) ? '' : $values['search']);

        $this->response->html($this->renderBoard($project_id));
    }

    /**
     * Enable collapsed mode.
     */
    public function collapse()
    {
        $this->changeDisplayMode(1);
    }

    /**
     * Enable expanded mode.
     */
    public function expand()
    {
        $this->changeDisplayMode(0);
    }

    /**
     * Change display mode.
     *
     * @param int $mode
     */
    private function changeDisplayMode($mode)
    {
        $project_id = $this->request->getIntegerParam('project_id');
        $this->userMetadataCacheDecorator->set(UserMetadataModel::KEY_BOARD_COLLAPSED.$project_id, $mode);

        if ($this->request->isAjax()) {
            $this->response->html($this->renderBoard($project_id));
        } else {
            $this->response->redirect($this->helper->url->to('BoardController', 'show', ['project_id' => $project_id]));
        }
    }

    /**
     * Render board.
     *
     * @param int $project_id
     *
     * @return string
     */
    protected function renderBoard($project_id)
    {
        return $this->template->render('board/table_container', [
            'project'                        => $this->projectModel->getById($project_id),
            'board_private_refresh_interval' => $this->settingModel->get('board_private_refresh_interval'),
            'board_highlight_period'         => $this->settingModel->get('board_highlight_period'),
            'swimlanes'                      => $this->taskLexer
                ->build($this->userSession->getFilters($project_id))
                ->format(BoardFormatter::getInstance($this->container)->withProjectId($project_id)),
        ]);
    }
}
