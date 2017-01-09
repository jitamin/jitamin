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
use Jitamin\Foundation\Controller\AccessForbiddenException;
use Jitamin\Foundation\Controller\PageNotFoundException;
use Jitamin\Model\UserMetadataModel;

/**
 * Comment Controller.
 */
class CommentController extends Controller
{
    /**
     * Add comment form.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws AccessForbiddenException
     * @throws PageNotFoundException
     */
    public function create(array $values = [], array $errors = [])
    {
        $task = $this->getTask();

        if (empty($values)) {
            $values = [
                'user_id' => $this->userSession->getId(),
                'task_id' => $task['id'],
            ];
        }

        $this->response->html($this->template->render('task/comment/create', [
            'values' => $values,
            'errors' => $errors,
            'task'   => $task,
        ]));
    }

    /**
     * Add a comment.
     */
    public function store()
    {
        $task = $this->getTask();
        $values = $this->request->getValues();

        list($valid, $errors) = $this->commentValidator->validateCreation($values);

        if ($valid) {
            if ($this->commentModel->create($values) !== false) {
                $this->flash->success(t('Comment added successfully.'));
            } else {
                $this->flash->failure(t('Unable to create your comment.'));
            }

            $this->response->redirect($this->helper->url->to('Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], 'comments'), true);
        } else {
            $this->create($values, $errors);
        }
    }

    /**
     * Edit a comment.
     *
     * @param array $values
     * @param array $errors
     *
     * @throws AccessForbiddenException
     * @throws PageNotFoundException
     */
    public function edit(array $values = [], array $errors = [])
    {
        $task = $this->getTask();
        $comment = $this->getComment();

        $this->response->html($this->template->render('task/comment/edit', [
            'values'  => empty($values) ? $comment : $values,
            'errors'  => $errors,
            'comment' => $comment,
            'task'    => $task,
            'title'   => t('Edit a comment'),
        ]));
    }

    /**
     * Update and validate a comment.
     */
    public function update()
    {
        $task = $this->getTask();
        $this->getComment();

        $values = $this->request->getValues();
        list($valid, $errors) = $this->commentValidator->validateModification($values);

        if ($valid) {
            if ($this->commentModel->update($values)) {
                $this->flash->success(t('Comment updated successfully.'));
            } else {
                $this->flash->failure(t('Unable to update your comment.'));
            }

            return $this->response->redirect($this->helper->url->to('Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']]), false);
        }

        return $this->edit($values, $errors);
    }

    /**
     * Remove a comment.
     */
    public function remove()
    {
        $task = $this->getTask();
        $comment = $this->getComment();

        if ($this->request->isPost()) {
            $this->request->checkCSRFToken();
            if ($this->commentModel->remove($comment['id'])) {
                $this->flash->success(t('Comment removed successfully.'));
            } else {
                $this->flash->failure(t('Unable to remove this comment.'));
            }

            return $this->response->redirect($this->helper->url->to('Task/TaskController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], 'comments'));
        }

        return $this->response->html($this->template->render('task/comment/remove', [
            'comment' => $comment,
            'task'    => $task,
            'title'   => t('Remove a comment'),
        ]));
    }

    /**
     * Toggle comment sorting.
     */
    public function toggleSorting()
    {
        $task = $this->getTask();

        $oldDirection = $this->userMetadataCacheDecorator->get(UserMetadataModel::KEY_COMMENT_SORTING_DIRECTION, 'ASC');
        $newDirection = $oldDirection === 'ASC' ? 'DESC' : 'ASC';

        $this->userMetadataCacheDecorator->set(UserMetadataModel::KEY_COMMENT_SORTING_DIRECTION, $newDirection);

        $this->response->redirect($this->helper->url->to(
            'Task/TaskController',
            'show',
            ['task_id' => $task['id'], 'project_id' => $task['project_id']],
            'comments'
        ));
    }

    /**
     * Get the current comment.
     *
     * @throws PageNotFoundException
     * @throws AccessForbiddenException
     *
     * @return array
     */
    protected function getComment()
    {
        $comment = $this->commentModel->getById($this->request->getIntegerParam('comment_id'));

        if (empty($comment)) {
            throw new PageNotFoundException();
        }

        if (!$this->userSession->isAdmin() && $comment['user_id'] != $this->userSession->getId()) {
            throw new AccessForbiddenException();
        }

        return $comment;
    }
}
