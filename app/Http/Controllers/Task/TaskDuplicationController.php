<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Controller\Task;

use Jitamin\Controller\BaseController;

/**
 * Task Duplication controller.
 */
class TaskDuplicationController extends BaseController
{
    /**
     * Duplicate a task.
     */
    public function duplicate()
    {
        $task = $this->getTask();

        if ($this->request->getStringParam('confirmation') === 'yes') {
            $this->checkCSRFParam();
            $task_id = $this->taskDuplicationModel->duplicate($task['id']);

            if ($task_id > 0) {
                $this->flash->success(t('Task created successfully.'));

                return $this->response->redirect($this->helper->url->to('TaskController', 'show', ['project_id' => $task['project_id'], 'task_id' => $task_id]));
            } else {
                $this->flash->failure(t('Unable to create this task.'));

                return $this->response->redirect($this->helper->url->to('TaskDuplicationController', 'duplicate', ['project_id' => $task['project_id'], 'task_id' => $task['id']]), true);
            }
        }

        return $this->response->html($this->template->render('task_duplication/duplicate', [
            'task' => $task,
        ]));
    }

    /**
     * Move a task to another project.
     */
    public function move()
    {
        $task = $this->getTask();

        if ($this->request->isPost()) {
            $values = $this->request->getValues();
            list($valid) = $this->taskValidator->validateProjectModification($values);

            if ($valid && $this->taskProjectMoveModel->moveToProject($task['id'],
                                                                $values['project_id'],
                                                                $values['swimlane_id'],
                                                                $values['column_id'],
                                                                $values['category_id'],
                                                                $values['owner_id'])) {
                $this->flash->success(t('Task updated successfully.'));

                return $this->response->redirect($this->helper->url->to('TaskController', 'show', ['project_id' => $values['project_id'], 'task_id' => $task['id']]));
            }

            $this->flash->failure(t('Unable to update your task.'));
        }

        return $this->chooseDestination($task, 'task_duplication/move');
    }

    /**
     * Duplicate a task to another project.
     */
    public function copy()
    {
        $task = $this->getTask();

        if ($this->request->isPost()) {
            $values = $this->request->getValues();
            list($valid) = $this->taskValidator->validateProjectModification($values);

            if ($valid) {
                $task_id = $this->taskProjectDuplicationModel->duplicateToProject(
                    $task['id'], $values['project_id'], $values['swimlane_id'],
                    $values['column_id'], $values['category_id'], $values['owner_id']
                );

                if ($task_id > 0) {
                    $this->flash->success(t('Task created successfully.'));

                    return $this->response->redirect($this->helper->url->to('TaskController', 'show', ['project_id' => $values['project_id'], 'task_id' => $task_id]));
                }
            }

            $this->flash->failure(t('Unable to create your task.'));
        }

        return $this->chooseDestination($task, 'task_duplication/copy');
    }

    /**
     * Choose destination when move/copy task to another project.
     *
     * @param array  $task
     * @param string $template
     */
    private function chooseDestination(array $task, $template)
    {
        $values = [];
        $projects_list = $this->projectUserRoleModel->getActiveProjectsByUser($this->userSession->getId());

        unset($projects_list[$task['project_id']]);

        if (!empty($projects_list)) {
            $dst_project_id = $this->request->getIntegerParam('dst_project_id', key($projects_list));

            $swimlanes_list = $this->swimlaneModel->getList($dst_project_id, false, true);
            $columns_list = $this->columnModel->getList($dst_project_id);
            $categories_list = $this->categoryModel->getList($dst_project_id);
            $users_list = $this->projectUserRoleModel->getAssignableUsersList($dst_project_id);

            $values = $this->taskDuplicationModel->checkDestinationProjectValues($task);
            $values['project_id'] = $dst_project_id;
        } else {
            $swimlanes_list = [];
            $columns_list = [];
            $categories_list = [];
            $users_list = [];
        }

        $this->response->html($this->template->render($template, [
            'values'          => $values,
            'task'            => $task,
            'projects_list'   => $projects_list,
            'swimlanes_list'  => $swimlanes_list,
            'columns_list'    => $columns_list,
            'categories_list' => $categories_list,
            'users_list'      => $users_list,
        ]));
    }
}
