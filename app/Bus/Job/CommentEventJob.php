<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Bus\Job;

use Jitamin\Bus\EventBuilder\CommentEventBuilder;
use Jitamin\Model\CommentModel;

/**
 * Class CommentEventJob.
 */
class CommentEventJob extends BaseJob
{
    /**
     * Set job params.
     *
     * @param int    $commentId
     * @param string $eventName
     *
     * @return $this
     */
    public function withParams($commentId, $eventName)
    {
        $this->jobParams = [$commentId, $eventName];

        return $this;
    }

    /**
     * Execute job.
     *
     * @param int    $commentId
     * @param string $eventName
     *
     * @return $this
     */
    public function execute($commentId, $eventName)
    {
        $event = CommentEventBuilder::getInstance($this->container)
            ->withCommentId($commentId)
            ->buildEvent();

        if ($event !== null) {
            $this->dispatcher->dispatch($eventName, $event);

            if ($eventName === CommentModel::EVENT_CREATE) {
                $this->userMentionModel->fireEvents($event['comment']['comment'], CommentModel::EVENT_USER_MENTION, $event);
            }
        }
    }
}
