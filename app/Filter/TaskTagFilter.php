<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Filter;

use Hiject\Core\Filter\FilterInterface;
use Hiject\Model\TagModel;
use Hiject\Model\TaskModel;
use Hiject\Model\TaskTagModel;
use PicoDb\Database;

/**
 * Class TaskTagFilter
 */
class TaskTagFilter extends BaseFilter implements FilterInterface
{
    /**
     * Database object
     *
     * @access private
     * @var Database
     */
    private $db;

    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return ['tag'];
    }

    /**
     * Set database object
     *
     * @access public
     * @param  Database $db
     * @return $this
     */
    public function setDatabase(Database $db)
    {
        $this->db = $db;
        return $this;
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {
        if ($this->value === 'none') {
            $task_ids = $this->getTaskIdsWithoutTags();
        } else {
            $task_ids = $this->getTaskIdsWithGivenTag();
        }

        if (empty($task_ids)) {
            $task_ids = [-1];
        }

        $this->query->in(TaskModel::TABLE.'.id', $task_ids);

        return $this;
    }

    protected function getTaskIdsWithoutTags()
    {
        return $this->db
            ->table(TaskModel::TABLE)
            ->asc(TaskModel::TABLE . '.project_id')
            ->left(TaskTagModel::TABLE, 'tg', 'task_id', TaskModel::TABLE, 'id')
            ->isNull('tg.tag_id')
            ->findAllByColumn(TaskModel::TABLE . '.id');
    }

    protected function getTaskIdsWithGivenTag()
    {
        return $this->db
            ->table(TagModel::TABLE)
            ->ilike(TagModel::TABLE.'.name', $this->value)
            ->asc(TagModel::TABLE.'.project_id')
            ->join(TaskTagModel::TABLE, 'tag_id', 'id')
            ->findAllByColumn(TaskTagModel::TABLE.'.task_id');
    }
}
