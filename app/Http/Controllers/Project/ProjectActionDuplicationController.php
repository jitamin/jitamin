<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Project;

use Jitamin\Http\Controllers\Controller;

/**
 * Duplicate automatic action from another project.
 */
class ProjectActionDuplicationController extends Controller
{
    /**
     * Show project action duplication.
     */
    public function show()
    {
        $project = $this->getProject();
        $projects = $this->projectUserRoleModel->getProjectsByUser($this->userSession->getId());
        unset($projects[$project['id']]);

        $this->response->html($this->template->render('project/action/duplication', [
            'project'       => $project,
            'projects_list' => $projects,
        ]));
    }

    /**
     * Validate and save a new project action duplication.
     */
    public function store()
    {
        $project = $this->getProject();
        $src_project_id = $this->request->getValue('src_project_id');

        if ($this->actionModel->duplicate($src_project_id, $project['id'])) {
            $this->flash->success(t('Actions duplicated successfully.'));
        } else {
            $this->flash->failure(t('Unable to duplicate actions.'));
        }

        $this->response->redirect($this->helper->url->to('Project/ActionController', 'index', ['project_id' => $project['id']]));
    }
}
