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
use Hiject\Model\ProjectModel;
use Hiject\Model\TaskModel;

/**
 * Task AutoComplete Formatter.
 */
class TaskAutoCompleteFormatter extends BaseFormatter implements FormatterInterface
{
    /**
     * Apply formatter.
     *
     * @return array
     */
    public function format()
    {
        $tasks = $this->query->columns(
            TaskModel::TABLE.'.id',
            TaskModel::TABLE.'.title',
            ProjectModel::TABLE.'.name AS project_name'
        )->asc(TaskModel::TABLE.'.id')->findAll();

        foreach ($tasks as &$task) {
            $task['value'] = $task['title'];
            $task['label'] = $task['project_name'].' > #'.$task['id'].' '.$task['title'];
        }

        return $tasks;
    }
}
