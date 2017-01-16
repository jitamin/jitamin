<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Export;

use Jitamin\Foundation\Base;

/**
 * Transition Export.
 */
class TransitionExport extends Base
{
    /**
     * Get project export.
     *
     * @param int   $project_id Project id
     * @param mixed $from       Start date (timestamp or user formatted date)
     * @param mixed $to         End date (timestamp or user formatted date)
     *
     * @return array
     */
    public function export($project_id, $from, $to)
    {
        $results = [$this->getColumns()];
        $transitions = $this->transitionModel->getAllByProjectAndDate($project_id, $from, $to);

        foreach ($transitions as $transition) {
            $results[] = $this->format($transition);
        }

        return $results;
    }

    /**
     * Get column titles.
     *
     * @return string[]
     */
    protected function getColumns()
    {
        return [
            l('Id'),
            l('Task Title'),
            l('Source column'),
            l('Destination column'),
            l('Executer'),
            l('Date'),
            l('Time spent'),
        ];
    }

    /**
     * Format the output of a transition array.
     *
     * @param array $transition
     *
     * @return array
     */
    protected function format(array $transition)
    {
        $values = [
            (int) $transition['id'],
            $transition['title'],
            $transition['src_column'],
            $transition['dst_column'],
            $transition['name'] ?: $transition['username'],
            date($this->dateParser->getUserDateTimeFormat(), $transition['date']),
            round($transition['time_spent'] / 3600, 2),
        ];

        return $values;
    }
}
