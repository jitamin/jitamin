<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Api\Procedure;

use Hiject\Model\SubtaskModel;

/**
 * Me API controller.
 */
class MeProcedure extends BaseProcedure
{
    public function getMe()
    {
        return $this->sessionStorage->user;
    }

    public function getMyDashboard()
    {
        $user_id = $this->userSession->getId();
        $projects = $this->projectModel->getQueryColumnStats($this->projectPermissionModel->getActiveProjectIds($user_id))->findAll();
        $tasks = $this->taskFinderModel->getUserQuery($user_id)->findAll();

        return [
            'projects' => $this->formatProjects($projects),
            'tasks'    => $this->formatTasks($tasks),
            'subtasks' => $this->subtaskModel->getUserQuery($user_id, [SubtaskModel::STATUS_TODO, SubtaskModel::STATUS_INPROGRESS])->findAll(),
        ];
    }

    public function getMyActivity()
    {
        $project_ids = $this->projectPermissionModel->getActiveProjectIds($this->userSession->getId());

        return $this->helper->projectActivity->getProjectsEvents($project_ids, 100);
    }

    public function createMyPrivateProject($name, $description = null)
    {
        if ($this->settingModel->get('disable_private_project', 0) == 1) {
            return false;
        }

        $values = [
            'name'        => $name,
            'description' => $description,
            'is_private'  => 1,
        ];

        list($valid) = $this->projectValidator->validateCreation($values);

        return $valid ? $this->projectModel->create($values, $this->userSession->getId(), true) : false;
    }

    public function getMyProjectsList()
    {
        return $this->projectUserRoleModel->getProjectsByUser($this->userSession->getId());
    }

    public function getMyOverdueTasks()
    {
        return $this->taskFinderModel->getOverdueTasksByUser($this->userSession->getId());
    }

    public function getMyProjects()
    {
        $project_ids = $this->projectPermissionModel->getActiveProjectIds($this->userSession->getId());
        $projects = $this->projectModel->getAllByIds($project_ids);

        return $this->formatProjects($projects);
    }
}
