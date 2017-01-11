<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Project;

use Jitamin\Http\Controllers\Controller;

/**
 * Export Controller.
 */
class ExportController extends Controller
{
    /**
     * Task export.
     */
    public function tasks()
    {
        $this->common('taskExport', 'export', t('Tasks'), 'tasks', t('Tasks Export'));
    }

    /**
     * Subtask export.
     */
    public function subtasks()
    {
        $this->common('subtaskExport', 'export', t('Subtasks'), 'subtasks', t('Subtasks Export'));
    }

    /**
     * Daily project summary export.
     */
    public function summary()
    {
        $this->common('projectDailyColumnStatsModel', 'getAggregatedMetrics', t('Summary'), 'summary', t('Daily project summary export'));
    }

    /**
     * Transition export.
     */
    public function transitions()
    {
        $this->common('transitionExport', 'export', t('Transitions'), 'transitions', t('Task transitions export'));
    }

    /**
     * Common export method.
     *
     * @param string $model
     * @param string $method
     * @param string $filename
     * @param string $action
     * @param string $page_title
     *
     * @throws \Jitamin\Foundation\Exceptions\PageNotFoundException
     */
    protected function common($model, $method, $filename, $action, $page_title)
    {
        $project = $this->getProject();
        $from = $this->request->getStringParam('from');
        $to = $this->request->getStringParam('to');

        if ($from && $to) {
            $data = $this->$model->$method($project['id'], $from, $to);
            $this->response->withFileDownload($filename.'.csv');
            $this->response->csv($data);
        } else {
            $this->response->html($this->helper->layout->project('project/export/'.$action, [
                'values' => [
                    'controller' => 'Project/ExportController',
                    'action'     => $action,
                    'project_id' => $project['id'],
                    'from'       => $from,
                    'to'         => $to,
                ],
                'errors'  => [],
                'project' => $project,
                'title'   => $page_title,
            ], 'project/export/subside'));
        }
    }
}
