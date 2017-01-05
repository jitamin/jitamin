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
use Jitamin\Model\UserModel;

/**
 * Class UserPagination.
 */
class UserPagination extends Base
{
    /**
     * Get user listing paginator.
     *
     * @return Paginator
     */
    public function getListingPaginator()
    {
        return $this->paginator
            ->setUrl('Admin/UserController', 'index')
            ->setMax(30)
            ->setOrder(UserModel::TABLE.'.username')
            ->setQuery($this->userModel->getQuery())
            ->calculate();
    }
}
