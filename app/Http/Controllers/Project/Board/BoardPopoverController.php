<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Project\Board;

use Jitamin\Controller\BaseController;

/**
 * Board Popover Controller.
 */
class BoardPopoverController extends BaseController
{
    /**
     * Confirmation before to close all column tasks.
     */
    public function confirmCloseColumnTasks()
    {
        $project = $this->getProject();
        $column_id = $this->request->getIntegerParam('column_id');
        $swimlane_id = $this->request->getIntegerParam('swimlane_id');

        $this->response->html($this->template->render('project/board/close_all_tasks_column', [
            'project'  => $project,
            'nb_tasks' => $this->taskFinderModel->countByColumnAndSwimlaneId($project['id'], $column_id, $swimlane_id),
            'column'   => $this->columnModel->getColumnTitleById($column_id),
            'swimlane' => $this->swimlaneModel->getNameById($swimlane_id) ?: t($project['default_swimlane']),
            'values'   => ['column_id' => $column_id, 'swimlane_id' => $swimlane_id],
        ]));
    }

    /**
     * Close all column tasks.
     */
    public function closeColumnTasks()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        $this->taskStatusModel->closeTasksBySwimlaneAndColumn($values['swimlane_id'], $values['column_id']);
        $this->flash->success(t('All tasks of the column "%s" and the swimlane "%s" have been closed successfully.', $this->columnModel->getColumnTitleById($values['column_id']), $this->swimlaneModel->getNameById($values['swimlane_id']) ?: t($project['default_swimlane'])));
        $this->response->redirect($this->helper->url->to('Project/Board/BoardController', 'show', ['project_id' => $project['id']]));
    }
}
