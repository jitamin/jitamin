<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Controller;

/**
 * Class User List Controller
 */
class UserListController extends BaseController
{
    /**
     * List all users
     *
     * @access public
     */
    public function show()
    {
        $paginator = $this->userPagination->getListingPaginator();

        $this->response->html($this->helper->layout->app('user_list/show', array(
            'title' => t('Users').' ('.$paginator->getTotal().')',
            'paginator' => $paginator,
        )));
    }
}
