<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Dashboard;

use Jitamin\Http\Controllers\Controller;

/**
 * Project Controller.
 */
class ProjectController extends Controller
{
    /**
     * Project overview.
     */
    public function index()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/project/index', [
            'title'             => t('Dashboard'),
            'paginator'         => $this->projectPagination->getDashboardPaginator($user['id'], 'index', 10),
            'user'              => $user,
        ]));
    }

    /**
     * Starred projects.
     */
    public function starred()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('dashboard/project/starred', [
            'title'             => t('Starred projects'),
            'paginator'         => $this->starPagination->getDashboardPaginator($user['id'], 'starred', 10),
            'user'              => $user,
        ]));
    }
}
