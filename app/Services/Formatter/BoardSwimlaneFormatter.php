<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Formatter;

use Hiject\Core\Filter\FormatterInterface;

/**
 * Board Swimlane Formatter.
 */
class BoardSwimlaneFormatter extends BaseFormatter implements FormatterInterface
{
    protected $swimlanes = [];
    protected $columns = [];
    protected $tasks = [];
    protected $tags = [];

    /**
     * Set swimlanes.
     *
     * @param array $swimlanes
     *
     * @return $this
     */
    public function withSwimlanes(array $swimlanes)
    {
        $this->swimlanes = $swimlanes;

        return $this;
    }

    /**
     * Set columns.
     *
     * @param array $columns
     *
     * @return $this
     */
    public function withColumns(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Set tasks.
     *
     * @param array $tasks
     *
     * @return $this
     */
    public function withTasks(array $tasks)
    {
        $this->tasks = $tasks;

        return $this;
    }

    /**
     * Set tags.
     *
     * @param array $tags
     *
     * @return $this
     */
    public function withTags(array $tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Apply formatter.
     *
     * @return array
     */
    public function format()
    {
        $nb_swimlanes = count($this->swimlanes);
        $nb_columns = count($this->columns);

        foreach ($this->swimlanes as &$swimlane) {
            $swimlane['id'] = (int) $swimlane['id'];
            $swimlane['columns'] = BoardColumnFormatter::getInstance($this->container)
                ->withSwimlaneId($swimlane['id'])
                ->withColumns($this->columns)
                ->withTasks($this->tasks)
                ->withTags($this->tags)
                ->format();

            $swimlane['nb_swimlanes'] = $nb_swimlanes;
            $swimlane['nb_columns'] = $nb_columns;
            $swimlane['nb_tasks'] = array_column_sum($swimlane['columns'], 'nb_tasks');
            $swimlane['score'] = array_column_sum($swimlane['columns'], 'score');

            $this->calculateStatsByColumnAcrossSwimlanes($swimlane['columns']);
        }

        return $this->swimlanes;
    }

    /**
     * Calculate stats for each column acrosss all swimlanes.
     *
     * @param array $columns
     */
    protected function calculateStatsByColumnAcrossSwimlanes(array $columns)
    {
        foreach ($columns as $columnIndex => $column) {
            if (!isset($this->swimlanes[0]['columns'][$columnIndex]['column_nb_tasks'])) {
                $this->swimlanes[0]['columns'][$columnIndex]['column_nb_tasks'] = 0;
                $this->swimlanes[0]['columns'][$columnIndex]['column_score'] = 0;
            }

            $this->swimlanes[0]['columns'][$columnIndex]['column_nb_tasks'] += $column['nb_tasks'];
            $this->swimlanes[0]['columns'][$columnIndex]['column_score'] += $column['score'];
        }
    }
}
