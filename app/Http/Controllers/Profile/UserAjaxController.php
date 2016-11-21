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

use Hiject\Filter\UserNameFilter;
use Hiject\Formatter\UserAutoCompleteFormatter;
use Hiject\Model\UserModel;

/**
 * User Ajax Controller
 */
class UserAjaxController extends BaseController
{
    /**
     * User auto-completion (Ajax)
     *
     * @access public
     */
    public function autocomplete()
    {
        $search = $this->request->getStringParam('term');
        $filter = $this->userQuery->withFilter(new UserNameFilter($search));
        $filter->getQuery()->asc(UserModel::TABLE.'.name')->asc(UserModel::TABLE.'.username');
        $this->response->json($filter->format(new UserAutoCompleteFormatter($this->container)));
    }

    /**
     * User mention auto-completion (Ajax)
     *
     * @access public
     */
    public function mention()
    {
        $project_id = $this->request->getStringParam('project_id');
        $query = $this->request->getStringParam('q');
        $users = $this->projectPermissionModel->findUsernames($project_id, $query);
        $this->response->json($users);
    }

    /**
     * Check if the user is connected
     *
     * @access public
     */
    public function status()
    {
        $this->response->text('OK');
    }
}
