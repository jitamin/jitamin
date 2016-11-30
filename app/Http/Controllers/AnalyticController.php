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

use Hiject\Filter\TaskProjectFilter;
use Hiject\Model\TaskModel;

/**
 * Project Analytic Controller.
 */
class AnalyticController extends BaseController
{
    /**
     * Show average Lead and Cycle time.
     */
    public function leadAndCycleTime()
    {
        $project = $this->getProject();
        list($from, $to) = $this->getDates();

        $this->response->html($this->helper->layout->analytic('analytic/lead_cycle_time', [
            'values' => [
                'from' => $from,
                'to'   => $to,
            ],
            'project' => $project,
            'average' => $this->averageLeadCycleTimeAnalytic->build($project['id']),
            'metrics' => $this->projectDailyStatsModel->getRawMetrics($project['id'], $from, $to),
            'title'   => t('Lead and cycle time'),
        ]));
    }

    /**
     * Show comparison between actual and estimated hours chart.
     */
    public function timeComparison()
    {
        $project = $this->getProject();

        $paginator = $this->paginator
            ->setUrl('AnalyticController', 'timeComparison', ['project_id' => $project['id']])
            ->setMax(30)
            ->setOrder(TaskModel::TABLE.'.id')
            ->setQuery($this->taskQuery
                ->withFilter(new TaskProjectFilter($project['id']))
                ->getQuery()
            )
            ->calculate();

        $this->response->html($this->helper->layout->analytic('analytic/time_comparison', [
            'project'   => $project,
            'paginator' => $paginator,
            'metrics'   => $this->estimatedTimeComparisonAnalytic->build($project['id']),
            'title'     => t('Estimated vs actual time'),
        ]));
    }

    /**
     * Show average time spent by column.
     */
    public function averageTimeByColumn()
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->analytic('analytic/avg_time_columns', [
            'project' => $project,
            'metrics' => $this->averageTimeSpentColumnAnalytic->build($project['id']),
            'title'   => t('Average time into each column'),
        ]));
    }

    /**
     * Show tasks distribution graph.
     */
    public function taskDistribution()
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->analytic('analytic/task_distribution', [
            'project' => $project,
            'metrics' => $this->taskDistributionAnalytic->build($project['id']),
            'title'   => t('Task distribution'),
        ]));
    }

    /**
     * Show users repartition.
     */
    public function userDistribution()
    {
        $project = $this->getProject();

        $this->response->html($this->helper->layout->analytic('analytic/user_distribution', [
            'project' => $project,
            'metrics' => $this->userDistributionAnalytic->build($project['id']),
            'title'   => t('User repartition'),
        ]));
    }

    /**
     * Show cumulative flow diagram.
     */
    public function cfd()
    {
        $this->commonAggregateMetrics('analytic/cfd', 'total', t('Cumulative flow diagram'));
    }

    /**
     * Show burndown chart.
     */
    public function burndown()
    {
        $this->commonAggregateMetrics('analytic/burndown', 'score', t('Burndown chart'));
    }

    /**
     * Common method for CFD and Burdown chart.
     *
     * @param string $template
     * @param string $column
     * @param string $title
     */
    private function commonAggregateMetrics($template, $column, $title)
    {
        $project = $this->getProject();
        list($from, $to) = $this->getDates();

        $display_graph = $this->projectDailyColumnStatsModel->countDays($project['id'], $from, $to) >= 2;

        $this->response->html($this->helper->layout->analytic($template, [
            'values' => [
                'from' => $from,
                'to'   => $to,
            ],
            'display_graph' => $display_graph,
            'metrics'       => $display_graph ? $this->projectDailyColumnStatsModel->getAggregatedMetrics($project['id'], $from, $to, $column) : [],
            'project'       => $project,
            'title'         => $title,
        ]));
    }

    private function getDates()
    {
        $values = $this->request->getValues();

        $from = $this->request->getStringParam('from', date('Y-m-d', strtotime('-1week')));
        $to = $this->request->getStringParam('to', date('Y-m-d'));

        if (!empty($values)) {
            $from = $this->dateParser->getIsoDate($values['from']);
            $to = $this->dateParser->getIsoDate($values['to']);
        }

        return [$from, $to];
    }
}
