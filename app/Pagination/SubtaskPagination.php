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
use Hiject\Model\SubtaskModel;
use Hiject\Model\TaskModel;

/**
 * Class SubtaskPagination
 */
class SubtaskPagination extends Base
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
        $query = $this->subtaskModel->getUserQuery($user_id, [SubtaskModel::STATUS_TODO, SubtaskModel::STATUS_INPROGRESS]);
        $this->hook->reference('pagination:dashboard:subtask:query', $query);

        return $this->paginator
            ->setUrl('DashboardController', $method, ['pagination' => 'subtasks', 'user_id' => $user_id])
            ->setMax($max)
            ->setOrder(TaskModel::TABLE.'.id')
            ->setQuery($query)
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'subtasks');
    }
}
