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

use Hiject\Bus\Event\GenericEvent;
use Hiject\Model\TaskModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Project modification date  Subscriber
 */
class ProjectModificationDateSubscriber extends BaseSubscriber implements EventSubscriberInterface
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
        return array(
            TaskModel::EVENT_CREATE_UPDATE   => 'execute',
            TaskModel::EVENT_CLOSE           => 'execute',
            TaskModel::EVENT_OPEN            => 'execute',
            TaskModel::EVENT_MOVE_SWIMLANE   => 'execute',
            TaskModel::EVENT_MOVE_COLUMN     => 'execute',
            TaskModel::EVENT_MOVE_POSITION   => 'execute',
            TaskModel::EVENT_MOVE_PROJECT    => 'execute',
            TaskModel::EVENT_ASSIGNEE_CHANGE => 'execute',
        );
    }

    /**
     * Handle the event
     *
     * @access public
     * @param GenericEvent $event
     */
    public function execute(GenericEvent $event)
    {
        $this->logger->debug('Subscriber executed: '.__METHOD__);
        $this->projectModel->updateModificationDate($event['task']['project_id']);
    }
}
