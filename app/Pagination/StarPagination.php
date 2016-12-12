<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Pagination;

use Hiject\Core\Base;
use Hiject\Core\Paginator;
use Hiject\Model\ProjectModel;

/**
 * Class StarPagination.
 */
class StarPagination extends Base
{
    /**
     * Get project listing paginator.
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
            ->setUrl('DashboardController', $method, ['pagination' => 'stars', 'user_id' => $user_id])
            ->setMax(30)
            ->setOrder(ProjectModel::TABLE.'.name')
            ->setQuery($this->projectModel->getQueryColumnStats($this->projectStarModel->getProjectIds($user_id)))
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'stars');
    }
}
