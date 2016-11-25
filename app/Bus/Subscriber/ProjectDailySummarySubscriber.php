<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Bus\Subscriber;

use Hiject\Bus\Event\TaskEvent;
use Hiject\Model\TaskModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Project daily summary Subscriber
 */
class ProjectDailySummarySubscriber extends BaseSubscriber implements EventSubscriberInterface
{
    /**
     * Get event listeners
     *
     * @static
     * @access public
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
     * Handle the event
     *
     * @access public
     * @param TaskEvent $event
     */
    public function execute(TaskEvent $event)
    {
        $this->logger->debug('Subscriber executed: '.__METHOD__);
        $this->queueManager->push($this->projectMetricJob->withParams($event['task']['project_id']));
    }
}
