<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Export;

use Hiject\Core\Base;

/**
 * Transition Export
 */
class TransitionExport extends Base
{
    /**
     * Get project export
     *
     * @access public
     * @param  integer    $project_id      Project id
     * @param  mixed      $from            Start date (timestamp or user formatted date)
     * @param  mixed      $to              End date (timestamp or user formatted date)
     * @return array
     */
    public function export($project_id, $from, $to)
    {
        $results = array($this->getColumns());
        $transitions = $this->transitionModel->getAllByProjectAndDate($project_id, $from, $to);

        foreach ($transitions as $transition) {
            $results[] = $this->format($transition);
        }

        return $results;
    }

    /**
     * Get column titles
     *
     * @access protected
     * @return string[]
     */
    protected function getColumns()
    {
        return array(
            e('Id'),
            e('Task Title'),
            e('Source column'),
            e('Destination column'),
            e('Executer'),
            e('Date'),
            e('Time spent'),
        );
    }

    /**
     * Format the output of a transition array
     *
     * @access protected
     * @param  array     $transition
     * @return array
     */
    protected function format(array $transition)
    {
        $values = array(
            (int) $transition['id'],
            $transition['title'],
            $transition['src_column'],
            $transition['dst_column'],
            $transition['name'] ?: $transition['username'],
            date($this->dateParser->getUserDateTimeFormat(), $transition['date']),
            round($transition['time_spent'] / 3600, 2)
        );

        return $values;
    }
}
