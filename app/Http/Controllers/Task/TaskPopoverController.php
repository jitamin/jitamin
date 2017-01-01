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
 * Task Popover.
 */
class TaskPopoverController extends BaseController
{
    /**
     * Screenshot popover.
     */
    public function screenshot()
    {
        $task = $this->getTask();

        $this->response->html($this->template->render('task_file/screenshot', [
            'task' => $task,
        ]));
    }
}
