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
use Hiject\Model\UserModel;

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
            ->setUrl('UserController', 'index')
            ->setMax(30)
            ->setOrder(UserModel::TABLE.'.username')
            ->setQuery($this->userModel->getQuery())
            ->calculate();
    }
}
