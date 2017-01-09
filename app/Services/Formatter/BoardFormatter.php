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

use Jitamin\Foundation\Filter\FormatterInterface;
use Jitamin\Model\TaskModel;

/**
 * Board Formatter.
 */
class BoardFormatter extends BaseFormatter implements FormatterInterface
{
    /**
     * Project id.
     *
     * @var int
     */
    protected $projectId;

    /**
     * Set ProjectId.
     *
     * @param int $projectId
     *
     * @return $this
     */
    public function withProjectId($projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Apply formatter.
     *
     * @return array
     */
    public function format()
    {
        $swimlanes = $this->swimlaneModel->getSwimlanes($this->projectId);
        $columns = $this->columnModel->getAll($this->projectId);

        if (empty($swimlanes) || empty($columns)) {
            return [];
        }

        $this->hook->reference('formatter:board:query', $this->query);

        $tasks = $this->query
            ->eq(TaskModel::TABLE.'.project_id', $this->projectId)
            ->asc(TaskModel::TABLE.'.position')
            ->findAll();

        $task_ids = array_column($tasks, 'id');
        $tags = $this->taskTagModel->getTagsByTasks($task_ids);

        return BoardSwimlaneFormatter::getInstance($this->container)
            ->withSwimlanes($swimlanes)
            ->withColumns($columns)
            ->withTasks($tasks)
            ->withTags($tags)
            ->format();
    }
}
