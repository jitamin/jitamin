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

use Jitamin\Bus\Event\TaskEvent;
use Jitamin\Model\TaskModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Project daily summary Subscriber.
 */
class ProjectDailySummarySubscriber extends BaseSubscriber implements EventSubscriberInterface
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
            TaskModel::EVENT_CREATE_UPDATE => 'execute',
            TaskModel::EVENT_CLOSE         => 'execute',
            TaskModel::EVENT_OPEN          => 'execute',
            TaskModel::EVENT_MOVE_COLUMN   => 'execute',
            TaskModel::EVENT_MOVE_SWIMLANE => 'execute',
        ];
    }

    /**
     * Handle the event.
     *
     * @param TaskEvent $event
     */
    public function execute(TaskEvent $event)
    {
        $this->logger->debug('Subscriber executed: '.__METHOD__);
        $this->queueManager->push($this->projectMetricJob->withParams($event['task']['project_id']));
    }
}
