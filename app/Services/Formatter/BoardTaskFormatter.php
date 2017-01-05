<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Formatter;

use Jitamin\Core\Filter\FormatterInterface;

/**
 * Board Task Formatter.
 */
class BoardTaskFormatter extends BaseFormatter implements FormatterInterface
{
    protected $tasks = [];
    protected $tags = [];
    protected $columnId = 0;
    protected $swimlaneId = 0;

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
     * Set columnId.
     *
     * @param int $columnId
     *
     * @return $this
     */
    public function withColumnId($columnId)
    {
        $this->columnId = $columnId;

        return $this;
    }

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
     * Apply formatter.
     *
     * @return array
     */
    public function format()
    {
        $tasks = array_values(array_filter($this->tasks, [$this, 'filterTasks']));
        array_merge_relation($tasks, $this->tags, 'tags', 'id');

        foreach ($tasks as &$task) {
            $task['is_draggable'] = $this->helper->projectRole->isDraggable($task);
        }

        return $tasks;
    }

    /**
     * Keep only tasks of the given column and swimlane.
     *
     * @param array $task
     *
     * @return bool
     */
    protected function filterTasks(array $task)
    {
        return $task['column_id'] == $this->columnId && $task['swimlane_id'] == $this->swimlaneId;
    }
}
