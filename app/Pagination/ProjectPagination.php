<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Pagination;

use Jitamin\Core\Base;
use Jitamin\Core\Paginator;
use Jitamin\Model\ProjectModel;

/**
 * Class ProjectPagination.
 */
class ProjectPagination extends Base
{
    /**
     * Get dashboard pagination.
     *
     * @param int    $user_id
     * @param string $method
     * @param int    $max
     *
     * @return Paginator
     */
    public function getDashboardPaginator($user_id, $method, $max)
    {
        return $this->paginator
            ->setUrl('Dashboard/DashboardController', $method, ['pagination' => 'projects', 'user_id' => $user_id])
            ->setMax($max)
            ->setOrder(ProjectModel::TABLE.'.name')
            ->setQuery($this->projectModel->getQueryColumnStats($this->projectPermissionModel->getActiveProjectIds($user_id)))
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'projects');
    }
}
