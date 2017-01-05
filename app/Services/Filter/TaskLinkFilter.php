<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Filter;

use Jitamin\Core\Filter\FilterInterface;
use Jitamin\Model\LinkModel;
use Jitamin\Model\TaskLinkModel;
use Jitamin\Model\TaskModel;
use PicoDb\Database;
use PicoDb\Table;

/**
 * Filter tasks by link name.
 */
class TaskLinkFilter extends BaseFilter implements FilterInterface
{
    /**
     * Database object.
     *
     * @var Database
     */
    private $db;

    /**
     * Set database object.
     *
     * @param Database $db
     *
     * @return TaskLinkFilter
     */
    public function setDatabase(Database $db)
    {
        $this->db = $db;

        return $this;
    }

    /**
     * Get search attribute.
     *
     * @return string[]
     */
    public function getAttributes()
    {
        return ['link'];
    }

    /**
     * Apply filter.
     *
     * @return string
     */
    public function apply()
    {
        $task_ids = $this->getSubQuery()->findAllByColumn('task_id');

        if (!empty($task_ids)) {
            $this->query->in(TaskModel::TABLE.'.id', $task_ids);
        } else {
            $this->query->eq(TaskModel::TABLE.'.id', 0); // No match
        }
    }

    /**
     * Get subquery.
     *
     * @return Table
     */
    protected function getSubQuery()
    {
        return $this->db->table(TaskLinkModel::TABLE)
            ->columns(
                TaskLinkModel::TABLE.'.task_id',
                LinkModel::TABLE.'.label'
            )
            ->join(LinkModel::TABLE, 'id', 'link_id', TaskLinkModel::TABLE)
            ->ilike(LinkModel::TABLE.'.label', $this->value);
    }
}
