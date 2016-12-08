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

use Hiject\Core\Controller\AccessForbiddenException;

/**
 * Class TaskSuppressionController.
 */
class TaskSuppressionController extends BaseController
{
    /**
     * Confirmation dialog box before to remove the task.
     */
    public function confirm()
    {
        $task = $this->getTask();

        if (!$this->helper->projectRole->canRemoveTask($task)) {
            throw new AccessForbiddenException();
        }

        $this->response->html($this->template->render('task_suppression/remove', [
            'task'     => $task,
            'redirect' => $this->request->getStringParam('redirect'),
        ]));
    }

    /**
     * Remove a task.
     */
    public function remove()
    {
        $task = $this->getTask();
        $this->checkCSRFParam();

        if (!$this->helper->projectRole->canRemoveTask($task)) {
            throw new AccessForbiddenException();
        }

        if ($this->taskModel->remove($task['id'])) {
            $this->flash->success(t('Task removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this task.'));
        }

        $redirect = $this->request->getStringParam('redirect') === '';
        $this->response->redirect($this->helper->url->to('BoardController', 'show', ['project_id' => $task['project_id']]), $redirect);
    }
}
