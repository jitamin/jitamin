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
 * Task Status controller.
 */
class TaskStatusController extends BaseController
{
    /**
     * Close a task.
     */
    public function close()
    {
        $this->changeStatus('close', 'task_status/close', t('Task closed successfully.'), t('Unable to close this task.'));
    }

    /**
     * Open a task.
     */
    public function open()
    {
        $this->changeStatus('open', 'task_status/open', t('Task opened successfully.'), t('Unable to open this task.'));
    }

    /**
     * Common method to change status.
     *
     * @param string $method
     * @param string $template
     * @param string $success_message
     * @param string $failure_message
     */
    private function changeStatus($method, $template, $success_message, $failure_message)
    {
        $task = $this->getTask();

        if ($this->request->getStringParam('confirmation') === 'yes') {
            $this->checkCSRFParam();

            if ($this->taskStatusModel->$method($task['id'])) {
                $this->flash->success($success_message);
            } else {
                $this->flash->failure($failure_message);
            }

            return $this->response->redirect($this->helper->url->to('Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']]), true);
        }

        return $this->response->html($this->template->render($template, [
            'task' => $task,
        ]));
    }
}
