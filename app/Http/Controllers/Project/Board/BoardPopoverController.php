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

use Jitamin\Controller\Controller;

/**
 * Board Popover Controller.
 */
class BoardPopoverController extends Controller
{
    /**
     * Close all column tasks.
     */
    public function closeColumnTasks()
    {
        $project = $this->getProject();
        $column_id = $this->request->getIntegerParam('column_id');
        $swimlane_id = $this->request->getIntegerParam('swimlane_id');

        if ($this->request->isPost()) {
            $this->request->checkCSRFToken();
            $this->taskStatusModel->closeTasksBySwimlaneAndColumn($swimlane_id, $column_id);
            $this->flash->success(t('All tasks of the column "%s" and the swimlane "%s" have been closed successfully.', $this->columnModel->getColumnTitleById($column_id), $this->swimlaneModel->getNameById($swimlane_id) ?: t($project['default_swimlane'])));

            return $this->response->redirect($this->helper->url->to('Project/Board/BoardController', 'show', ['project_id' => $project['id']]));
        }

        return $this->response->html($this->template->render('project/board/close_all_tasks_column', [
            'project'     => $project,
            'nb_tasks'    => $this->taskFinderModel->countByColumnAndSwimlaneId($project['id'], $column_id, $swimlane_id),
            'column'      => $this->columnModel->getColumnTitleById($column_id),
            'swimlane'    => $this->swimlaneModel->getNameById($swimlane_id) ?: t($project['default_swimlane']),
            'column_id'   => $column_id,
            'swimlane_id' => $swimlane_id,
        ]));
    }
}
