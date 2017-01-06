<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Task;

use Jitamin\Controller\BaseController;

/**
 * Task File Controller.
 */
class TaskFileController extends BaseController
{
    /**
     * Screenshot.
     */
    public function screenshot()
    {
        $task = $this->getTask();

        if ($this->request->isPost() && $this->taskFileModel->uploadScreenshot($task['id'], $this->request->getValue('screenshot')) !== false) {
            $this->flash->success(t('Screenshot uploaded successfully.'));

            return $this->response->redirect($this->helper->url->to('Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']]), true);
        }

        return $this->response->html($this->template->render('task/attachment/screenshot', [
            'task' => $task,
        ]));
    }

    /**
     * File upload form.
     */
    public function create()
    {
        $task = $this->getTask();

        $this->response->html($this->template->render('task/attachment/create', [
            'task'     => $task,
            'max_size' => $this->helper->text->phpToBytes(get_upload_max_size()),
        ]));
    }

    /**
     * File upload (save files).
     */
    public function store()
    {
        $task = $this->getTask();

        if (!$this->taskFileModel->uploadFiles($task['id'], $this->request->getFileInfo('files'))) {
            $this->flash->failure(t('Unable to upload the file.'));
        }

        $this->response->redirect($this->helper->url->to('Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']]), true);
    }

    /**
     * Remove a file.
     */
    public function remove()
    {
        $this->checkCSRFParam();
        $task = $this->getTask();
        $file = $this->taskFileModel->getById($this->request->getIntegerParam('file_id'));

        if ($file['task_id'] == $task['id'] && $this->taskFileModel->remove($file['id'])) {
            $this->flash->success(t('File removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this file.'));
        }

        $this->response->redirect($this->helper->url->to('Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']]));
    }

    /**
     * Confirmation dialog before removing a file.
     */
    public function confirm()
    {
        $task = $this->getTask();
        $file = $this->taskFileModel->getById($this->request->getIntegerParam('file_id'));

        $this->response->html($this->template->render('task/attachment/remove', [
            'task' => $task,
            'file' => $file,
        ]));
    }
}
