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
use Hiject\Model\TaskModel;

/**
 * Class TaskPagination
 */
class TaskPagination extends Base
{
    /**
     * Get dashboard pagination
     *
     * @access public
     * @param  integer $user_id
     * @param  string  $method
     * @param  integer $max
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
