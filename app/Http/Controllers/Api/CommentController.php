<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Api;

use Jitamin\Policy\CommentPolicy;
use Jitamin\Policy\TaskPolicy;

/**
 * Comment API controller.
 */
class CommentController extends Controller
{
    /**
     * Get a comment.
     *
     * @param int $comment_id Comment id
     *
     * @return array
     */
    public function getComment($comment_id)
    {
        CommentPolicy::getInstance($this->container)->check($this->getClassName(), 'getComment', $comment_id);

        return $this->commentModel->getById($comment_id);
    }

    /**
     * Get all comments for a given task.
     *
     * @param int $task_id Task id
     *
     * @return array
     */
    public function getAllComments($task_id)
    {
        TaskPolicy::getInstance($this->container)->check($this->getClassName(), 'getAllComments', $task_id);

        return $this->commentModel->getAll($task_id);
    }

    /**
     * Remove a comment.
     *
     * @param int $comment_id Comment id
     *
     * @return bool
     */
    public function removeComment($comment_id)
    {
        CommentPolicy::getInstance($this->container)->check($this->getClassName(), 'removeComment', $comment_id);

        return $this->commentModel->remove($comment_id);
    }

    /**
     * Create a new comment.
     *
     * @param int    $task_id
     * @param int    $user_id
     * @param string $content
     * @param string $reference
     *
     * @return bool|int
     */
    public function createComment($task_id, $user_id, $content, $reference = '')
    {
        TaskPolicy::getInstance($this->container)->check($this->getClassName(), 'createComment', $task_id);

        $values = [
            'task_id'   => $task_id,
            'user_id'   => $user_id,
            'comment'   => $content,
            'reference' => $reference,
        ];

        list($valid) = $this->commentValidator->validateCreation($values);

        return $valid ? $this->commentModel->create($values) : false;
    }

    /**
     * Update a comment in the database.
     *
     * @param int    $id
     * @param string $content
     *
     * @return bool
     */
    public function updateComment($id, $content)
    {
        CommentPolicy::getInstance($this->container)->check($this->getClassName(), 'updateComment', $id);

        $values = [
            'id'      => $id,
            'comment' => $content,
        ];

        list($valid) = $this->commentValidator->validateModification($values);

        return $valid && $this->commentModel->update($values);
    }
}
