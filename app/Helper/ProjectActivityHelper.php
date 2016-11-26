<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Helper;

use Hiject\Core\Base;
use Hiject\Filter\ProjectActivityProjectIdFilter;
use Hiject\Filter\ProjectActivityProjectIdsFilter;
use Hiject\Filter\ProjectActivityTaskIdFilter;
use Hiject\Formatter\ProjectActivityEventFormatter;
use Hiject\Model\ProjectActivityModel;

/**
 * Project Activity Helper
 */
class ProjectActivityHelper extends Base
{
    /**
     * Search events
     *
     * @access public
     * @param  string $search
     * @return array
     */
    public function searchEvents($search)
    {
        $projects = $this->projectUserRoleModel->getActiveProjectsByUser($this->userSession->getId());
        $events = [];

        if ($search !== '') {
            $queryBuilder = $this->projectActivityLexer->build($search);
            $queryBuilder
                ->withFilter(new ProjectActivityProjectIdsFilter(array_keys($projects)))
                ->getQuery()
                ->desc(ProjectActivityModel::TABLE.'.id')
                ->limit(500)
            ;

            $events = $queryBuilder->format(new ProjectActivityEventFormatter($this->container));
        }

        return $events;
    }

    /**
     * Get project activity events
     *
     * @access public
     * @param  integer  $project_id
     * @param  int      $limit
     * @return array
     */
    public function getProjectEvents($project_id, $limit = 50)
    {
        $queryBuilder = $this->projectActivityQuery
            ->withFilter(new ProjectActivityProjectIdFilter($project_id));

        $queryBuilder->getQuery()
            ->desc(ProjectActivityModel::TABLE.'.id')
            ->limit($limit)
        ;

        return $queryBuilder->format(new ProjectActivityEventFormatter($this->container));
    }

    /**
     * Get projects activity events
     *
     * @access public
     * @param  int[]    $project_ids
     * @param  int      $limit
     * @return array
     */
    public function getProjectsEvents(array $project_ids, $limit = 50)
    {
        $queryBuilder = $this->projectActivityQuery
            ->withFilter(new ProjectActivityProjectIdsFilter($project_ids));

        $queryBuilder->getQuery()
            ->desc(ProjectActivityModel::TABLE.'.id')
            ->limit($limit)
        ;

        return $queryBuilder->format(new ProjectActivityEventFormatter($this->container));
    }

    /**
     * Get task activity events
     *
     * @access public
     * @param  integer $task_id
     * @return array
     */
    public function getTaskEvents($task_id)
    {
        $queryBuilder = $this->projectActivityQuery
            ->withFilter(new ProjectActivityTaskIdFilter($task_id));

        $queryBuilder->getQuery()->desc(ProjectActivityModel::TABLE.'.id');

        return $queryBuilder->format(new ProjectActivityEventFormatter($this->container));
    }
}
