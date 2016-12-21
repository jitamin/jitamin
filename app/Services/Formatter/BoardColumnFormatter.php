<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Formatter;

use Jitamin\Core\Filter\FormatterInterface;

/**
 * Board Column Formatter.
 */
class BoardColumnFormatter extends BaseFormatter implements FormatterInterface
{
    protected $swimlaneId = 0;
    protected $columns = [];
    protected $tasks = [];
    protected $tags = [];

    /**
     * Set swimlaneId.
     *
     * @param int $swimlaneId
     *
     * @return $this
     */
    public function withSwimlaneId($swimlaneId)
    {
        $this->swimlaneId = $swimlaneId;

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
        foreach ($this->columns as &$column) {
            $column['id'] = (int) $column['id'];
            $column['tasks'] = BoardTaskFormatter::getInstance($this->container)
                ->withTasks($this->tasks)
                ->withTags($this->tags)
                ->withSwimlaneId($this->swimlaneId)
                ->withColumnId($column['id'])
                ->format();

            $column['nb_tasks'] = count($column['tasks']);
            $column['score'] = (int) array_column_sum($column['tasks'], 'score');
        }

        return $this->columns;
    }
}
