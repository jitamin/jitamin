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

use Hiject\Core\Base;
use Hiject\Core\Controller\AccessForbiddenException;
use Hiject\Core\Controller\PageNotFoundException;

/**
 * Base Controller
 */
abstract class BaseController extends Base
{
    /**
     * Check if the CSRF token from the URL is correct
     *
     * @access protected
     */
    protected function checkCSRFParam()
    {
        if (! $this->token->validateCSRFToken($this->request->getStringParam('csrf_token'))) {
            throw new AccessForbiddenException();
        }
    }

    /**
     * Check webhook token
     *
     * @access protected
     */
    protected function checkWebhookToken()
    {
        if ($this->configModel->get('webhook_token') !== $this->request->getStringParam('token')) {
            throw AccessForbiddenException::getInstance()->withoutLayout();
        }
    }

    /**
     * Common method to get a task for task views
     *
     * @access protected
     * @return array
     * @throws PageNotFoundException
     * @throws AccessForbiddenException
     */
    protected function getTask()
    {
        $project_id = $this->request->getIntegerParam('project_id');
        $task = $this->taskFinderModel->getDetails($this->request->getIntegerParam('task_id'));

        if (empty($task)) {
            throw new PageNotFoundException();
        }

        if ($project_id !== 0 && $project_id != $task['project_id']) {
            throw new AccessForbiddenException();
        }

        return $task;
    }

    /**
     * Get Task or Project file
     *
     * @access protected
     * @return array
     * @throws PageNotFoundException
     * @throws AccessForbiddenException
     */
    protected function getFile()
    {
        $task_id = $this->request->getIntegerParam('task_id');
        $file_id = $this->request->getIntegerParam('file_id');
        $model = 'projectFileModel';

        if ($task_id > 0) {
            $model = 'taskFileModel';
            $project_id = $this->taskFinderModel->getProjectId($task_id);

            if ($project_id !== $this->request->getIntegerParam('project_id')) {
                throw new AccessForbiddenException();
            }
        }

        $file = $this->$model->getById($file_id);

        if (empty($file)) {
            throw new PageNotFoundException();
        }

        $file['model'] = $model;
        return $file;
    }

    /**
     * Common method to get a project
     *
     * @access protected
     * @param  integer      $project_id    Default project id
     * @return array
     * @throws PageNotFoundException
     */
    protected function getProject($project_id = 0)
    {
        $project_id = $this->request->getIntegerParam('project_id', $project_id);
        $project = $this->projectModel->getByIdWithOwner($project_id);

        if (empty($project)) {
            throw new PageNotFoundException();
        }

        return $project;
    }

    /**
     * Common method to get the user
     *
     * @access protected
     * @return array
     * @throws PageNotFoundException
     * @throws AccessForbiddenException
     */
    protected function getUser()
    {
        $user = $this->userModel->getById($this->request->getIntegerParam('user_id', $this->userSession->getId()));

        if (empty($user)) {
            throw new PageNotFoundException();
        }

        if (! $this->userSession->isAdmin() && $this->userSession->getId() != $user['id']) {
            throw new AccessForbiddenException();
        }

        return $user;
    }

    /**
     * Get the current subtask
     *
     * @access protected
     * @return array
     * @throws PageNotFoundException
     */
    protected function getSubtask()
    {
        $subtask = $this->subtaskModel->getById($this->request->getIntegerParam('subtask_id'));

        if (empty($subtask)) {
            throw new PageNotFoundException();
        }

        return $subtask;
    }
}
