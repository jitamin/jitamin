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
use Jitamin\Model\SubtaskModel;
use Jitamin\Model\TaskModel;

/**
 * Class SubtaskPagination.
 */
class SubtaskPagination extends Base
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
