<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Pagination;

use Jitamin\Core\Base;
use Jitamin\Core\Paginator;
use Jitamin\Model\TaskModel;

/**
 * Class TaskPagination.
 */
class TaskPagination extends Base
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
        $query = $this->taskFinderModel->getUserQuery($user_id);
        $this->hook->reference('pagination:dashboard:task:query', $query);

        return $this->paginator
            ->setUrl('DashboardController', $method, ['pagination' => 'tasks', 'user_id' => $user_id])
            ->setMax($max)
            ->setOrder(TaskModel::TABLE.'.id')
            ->setQuery($query)
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'tasks');
    }
}
