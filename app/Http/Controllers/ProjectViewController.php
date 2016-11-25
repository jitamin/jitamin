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
 * Project Overview Controller
 */
class ProjectViewController extends BaseController
{
    /**
     * Show project overview
     */
    public function show()
    {
        $project = $this->getProject();
        $this->projectModel->getColumnStats($project);

        $this->response->html($this->helper->layout->app('project_view/show', [
            'project' => $project,
            'title' => $project['name'],
            'description' => $this->helper->projectHeader->getDescription($project),
            'users' => $this->projectUserRoleModel->getAllUsersGroupedByRole($project['id']),
            'roles' => $this->projectRoleModel->getList($project['id']),
            'events' => $this->helper->projectActivity->getProjectEvents($project['id'], 10),
            'images' => $this->projectFileModel->getAllImages($project['id']),
            'files' => $this->projectFileModel->getAllDocuments($project['id']),
        ]));
    }
}
