<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Bus\EventBuilder;

use Hiject\Bus\Event\CommentEvent;
use Hiject\Model\CommentModel;

/**
 * Class CommentEventBuilder.
 */
class CommentEventBuilder extends BaseEventBuilder
{
    protected $commentId = 0;

    /**
     * Set commentId.
     *
     * @param int $commentId
     *
     * @return $this
     */
    public function withCommentId($commentId)
    {
        $this->commentId = $commentId;

        return $this;
    }

    /**
     * Build event data.
     *
     * @return CommentEvent|null
     */
    public function buildEvent()
    {
        $comment = $this->commentModel->getById($this->commentId);

        if (empty($comment)) {
            return;
        }

        return new CommentEvent([
            'comment' => $comment,
            'task'    => $this->taskFinderModel->getDetails($comment['task_id']),
        ]);
    }

    /**
     * Get event title with author.
     *
     * @param string $author
     * @param string $eventName
     * @param array  $eventData
     *
     * @return string
     */
    public function buildTitleWithAuthor($author, $eventName, array $eventData)
    {
        switch ($eventName) {
            case CommentModel::EVENT_UPDATE:
                return e('%s updated a comment on the task #%d', $author, $eventData['task']['id']);
            case CommentModel::EVENT_CREATE:
                return e('%s commented on the task #%d', $author, $eventData['task']['id']);
            case CommentModel::EVENT_DELETE:
                return e('%s removed a comment on the task #%d', $author, $eventData['task']['id']);
            case CommentModel::EVENT_USER_MENTION:
                return e('%s mentioned you in a comment on the task #%d', $author, $eventData['task']['id']);
            default:
                return '';
        }
    }

    /**
     * Get event title without author.
     *
     * @param string $eventName
     * @param array  $eventData
     *
     * @return string
     */
    public function buildTitleWithoutAuthor($eventName, array $eventData)
    {
        switch ($eventName) {
            case CommentModel::EVENT_CREATE:
                return e('New comment on task #%d', $eventData['comment']['task_id']);
            case CommentModel::EVENT_UPDATE:
                return e('Comment updated on task #%d', $eventData['comment']['task_id']);
            case CommentModel::EVENT_DELETE:
                return e('Comment removed on task #%d', $eventData['comment']['task_id']);
            case CommentModel::EVENT_USER_MENTION:
                return e('You were mentioned in a comment on the task #%d', $eventData['task']['id']);
            default:
                return '';
        }
    }
}
