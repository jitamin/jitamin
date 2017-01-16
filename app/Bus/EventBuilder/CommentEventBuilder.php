<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Bus\EventBuilder;

use Jitamin\Bus\Event\CommentEvent;
use Jitamin\Model\CommentModel;

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
                return l('%s updated a comment on the task #%d', $author, $eventData['task']['id']);
            case CommentModel::EVENT_CREATE:
                return l('%s commented on the task #%d', $author, $eventData['task']['id']);
            case CommentModel::EVENT_DELETE:
                return l('%s removed a comment on the task #%d', $author, $eventData['task']['id']);
            case CommentModel::EVENT_USER_MENTION:
                return l('%s mentioned you in a comment on the task #%d', $author, $eventData['task']['id']);
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
                return l('New comment on task #%d', $eventData['comment']['task_id']);
            case CommentModel::EVENT_UPDATE:
                return l('Comment updated on task #%d', $eventData['comment']['task_id']);
            case CommentModel::EVENT_DELETE:
                return l('Comment removed on task #%d', $eventData['comment']['task_id']);
            case CommentModel::EVENT_USER_MENTION:
                return l('You were mentioned in a comment on the task #%d', $eventData['task']['id']);
            default:
                return '';
        }
    }
}
