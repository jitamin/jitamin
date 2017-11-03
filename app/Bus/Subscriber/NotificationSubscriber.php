<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Bus\Subscriber;

use Jitamin\Bus\Event\GenericEvent;
use Jitamin\Model\CommentModel;
use Jitamin\Model\SubtaskModel;
use Jitamin\Model\TaskFileModel;
use Jitamin\Model\TaskLinkModel;
use Jitamin\Model\TaskModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Notification Subscriber.
 */
class NotificationSubscriber extends BaseSubscriber implements EventSubscriberInterface
{
    /**
     * Get event listeners.
     *
     * @static
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            TaskModel::EVENT_USER_MENTION      => 'handleEvent',
            TaskModel::EVENT_CREATE            => 'handleEvent',
            TaskModel::EVENT_UPDATE            => 'handleEvent',
            TaskModel::EVENT_CLOSE             => 'handleEvent',
            TaskModel::EVENT_OPEN              => 'handleEvent',
            TaskModel::EVENT_MOVE_COLUMN       => 'handleEvent',
            TaskModel::EVENT_MOVE_POSITION     => 'handleEvent',
            TaskModel::EVENT_MOVE_SWIMLANE     => 'handleEvent',
            TaskModel::EVENT_ASSIGNEE_CHANGE   => 'handleEvent',
            SubtaskModel::EVENT_CREATE         => 'handleEvent',
            SubtaskModel::EVENT_UPDATE         => 'handleEvent',
            SubtaskModel::EVENT_DELETE         => 'handleEvent',
            CommentModel::EVENT_CREATE         => 'handleEvent',
            CommentModel::EVENT_UPDATE         => 'handleEvent',
            CommentModel::EVENT_DELETE         => 'handleEvent',
            CommentModel::EVENT_USER_MENTION   => 'handleEvent',
            TaskFileModel::EVENT_CREATE        => 'handleEvent',
            TaskLinkModel::EVENT_CREATE_UPDATE => 'handleEvent',
            TaskLinkModel::EVENT_DELETE        => 'handleEvent',
        ];
    }

    /**
     * Handle the event.
     *
     * @param GenericEvent $event
     * @param string       $eventName
     */
    public function handleEvent(GenericEvent $event, $eventName)
    {
        $this->logger->debug('Subscriber executed: '.__METHOD__);
        $this->queueManager->push($this->notificationJob->withParams($event, $eventName));
    }
}
