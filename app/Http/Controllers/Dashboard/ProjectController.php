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
use Jitamin\Model\ProjectModel;

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

        $paginator = $this->paginator
            ->setUrl('Dashboard/ProjectController', 'index', ['pagination' => 'projects', 'user_id' => $user['id']])
            ->setMax(10)
            ->setOrder(ProjectModel::TABLE.'.id')
            ->setDirection('DESC')
            ->setQuery($this->projectModel->getQueryColumnStats($this->projectPermissionModel->getActiveProjectIds($user['id'])))
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'projects');

        $this->response->html($this->helper->layout->dashboard('dashboard/project/index', [
            'title'             => t('Dashboard'),
            'paginator'         => $paginator,
            'user'              => $user,
        ]));
    }

    /**
     * Starred projects.
     */
    public function starred()
    {
        $user = $this->getUser();

        $paginator = $this->paginator
            ->setUrl('Dashboard/ProjectController', 'starred', ['pagination' => 'starred', 'user_id' => $user['id']])
            ->setMax(10)
            ->setOrder(ProjectModel::TABLE.'.name')
            ->setQuery($this->projectModel->getQueryColumnStats($this->projectStarModel->getProjectIds($user['id'])))
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'starred');

        $this->response->html($this->helper->layout->dashboard('dashboard/project/starred', [
            'title'             => t('Starred projects'),
            'paginator'         => $paginator,
            'user'              => $user,
        ]));
    }
}
