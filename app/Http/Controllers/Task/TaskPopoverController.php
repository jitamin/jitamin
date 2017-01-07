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

use Jitamin\Controller\Controller;

/**
 * Task Popover.
 */
class TaskPopoverController extends Controller
{
    /**
     * Screenshot popover.
     */
    public function screenshot()
    {
        $task = $this->getTask();

        $this->response->html($this->template->render('task/attachment/screenshot', [
            'task' => $task,
        ]));
    }
}
