<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Helper;

use Jitamin\Core\Base;
use Jitamin\Filter\ProjectActivityProjectIdFilter;
use Jitamin\Filter\ProjectActivityProjectIdsFilter;
use Jitamin\Filter\ProjectActivityTaskIdFilter;
use Jitamin\Formatter\ProjectActivityEventFormatter;
use Jitamin\Model\ProjectActivityModel;

/**
 * Project Activity Helper.
 */
class ProjectActivityHelper extends Base
{
    /**
     * Search events.
     *
     * @param string $search
     * @param int    $limit
     *
     * @return array
     */
    public function searchEvents($search, $limit = 50)
    {
        $projects = $this->projectUserRoleModel->getActiveProjectsByUser($this->userSession->getId());
        $events = [];

        if ($search !== '') {
            $queryBuilder = $this->projectActivityLexer->build($search);
            $queryBuilder
                ->withFilter(new ProjectActivityProjectIdsFilter(array_keys($projects)))
                ->getQuery()
                ->desc(ProjectActivityModel::TABLE.'.id')
                ->limit($limit);

            $events = $queryBuilder->format(new ProjectActivityEventFormatter($this->container));
        }

        return $events;
    }

    /**
     * Get project activity events.
     *
     * @param int $project_id
     * @param int $limit
     *
     * @return array
     */
    public function getProjectEvents($project_id, $limit = 50)
    {
        $queryBuilder = $this->projectActivityQuery
            ->withFilter(new ProjectActivityProjectIdFilter($project_id));

        $queryBuilder->getQuery()
            ->desc(ProjectActivityModel::TABLE.'.id')
            ->limit($limit);

        return $queryBuilder->format(new ProjectActivityEventFormatter($this->container));
    }

    /**
     * Get projects activity events.
     *
     * @param int[] $project_ids
     * @param int   $limit
     *
     * @return array
     */
    public function getProjectsEvents(array $project_ids, $limit = 50)
    {
        $queryBuilder = $this->projectActivityQuery
            ->withFilter(new ProjectActivityProjectIdsFilter($project_ids));

        $queryBuilder->getQuery()
            ->desc(ProjectActivityModel::TABLE.'.id')
            ->limit($limit);

        return $queryBuilder->format(new ProjectActivityEventFormatter($this->container));
    }

    /**
     * Get task activity events.
     *
     * @param int $task_id
     *
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
